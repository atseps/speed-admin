<template>
  <page-view showCard showHeader autoHeight isBack :title="`${state.name}权限`">
    <template #extra>
      <s-button type="primary" :loading="state.spinning" @click="saveAuth">
        <s-icon v-show="!state.spinning" type="file-outlined" />保存
      </s-button>
    </template>
    <s-tree
      checkable
      ref="treeRef"
      :loading="state.loading"
      :treeData="state.treeData"
      @check="onCheck"
      :field-names="{ key: 'id' }"
      v-model:checkedKeys="state.checkedKeys"
    >
    </s-tree>
  </page-view>
</template>
<script setup lang="ts">
import { getTreeAuth, saveAuth as saveAuthApi } from "@/api/system/role";
import { getViewportOffset } from "@/utils/domUtils";
import { difference } from "lodash-es";
import { TreeProps, KeyType } from "@/components/Tree";
interface TreeNode {
  id: string;
  children?: TreeNode[];
}
const { responseNotice } = useMessage();
const route = useRoute();
const treeRef = ref();
const height = ref();
const state = reactive({
  checkedKeys: [] as TreeProps["checkedKeys"], //默认选中的key
  treeData: [], //数据源
  loading: false,
  roleid: "" as string,
  name: "",
  checked: [],
  spinning: false,
  halfCheckedKeys: [] as KeyType[] //半选的key
});

onMounted(() => {
  state.roleid = route.params.id as string;
  state.name = (route.query.name || "") as string;
  getAuthNode(state.roleid);
});

const getAuthNode = (id: string) => {
  state.loading = true;
  getTreeAuth(id)
    .then(async ({ data }) => {
      state.treeData = data.authNode;
      state.checked = data.checked;
      const childrenIds = getAllLeaf(state.treeData);
      await nextTick();
      state.checkedKeys = childrenIds.filter((val) => data.checked.indexOf(val) > -1);
      state.halfCheckedKeys = difference(state.checked, state.checkedKeys as KeyType[]);
      unref(treeRef).expandAll(true);
      setTreeHeight();
    })
    .finally(() => {
      state.loading = false;
    });
};

const setTreeHeight = () => {
  const { bottomIncludeBody } = getViewportOffset(treeRef.value.$el);
  const caleHight = 64 + 48 + 50;
  height.value = `${bottomIncludeBody - caleHight / 2}px`;
};

function getCheckedKeys() {
  return [...(state.checkedKeys as KeyType[]), ...state.halfCheckedKeys];
}

const saveAuth = () => {
  const data = {
    role_id: state.roleid,
    menu_id: getCheckedKeys()
  };
  state.spinning = true;
  saveAuthApi(data).then(res => {
    state.spinning = false;
    responseNotice(res);
  });
};

const onCheck = (checkedKeys: KeyType[], e: { halfCheckedKeys?: KeyType[] }) => {
  if (e.halfCheckedKeys) {
    state.halfCheckedKeys = [...e.halfCheckedKeys];
  }
};

const getAllLeaf = (data: TreeNode[]): KeyType[] => {
  const leafIds: KeyType[] = [];
  const traverse = (items: TreeNode[]) => {
    items.forEach(item => {
      if (!item.children?.length) {
        leafIds.push(item.id);
      } else {
        traverse(item.children);
      }
    });
  };

  traverse(data);
  return leafIds;
};
</script>

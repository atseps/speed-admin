<template>
  <s-modal-form v-bind="getBindValue">
    <s-tree-select
      label="上级部门"
      :field-names="{ label: 'title' }"
      :treeData="data"
      name="parent_id"
      placeholder="请选择上级部门"
      v-model="form.parent_id"
    />
    <s-input label="部门名称" name="name" v-model="form.name" placeholder="请输入部门名称" />
    <a-form-item label="负责人" name="leader_id">
      <s-select-user v-model="form.leader_id" placeholder="请选择部门负责人" />
    </a-form-item>
    <s-input-number label="排序" v-model="form.sort"  max="9999" placeholder="请输入排序" />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/system/department";

export interface TreeData {
  id?: number;
  value: number;
  title: string;
  children: TreeData[];
}

const form = reactive({
  id: undefined,
  parent_id: undefined,
  sort: undefined,
  name: "",
  leader_id: ""
});

const rules = reactive({
  name: [{ required: true, message: "请输入部门名称" }],
  parent_id: [{ required: true, message: "请选择上级部门" }]
});

const data = ref<TreeData[]>([]);

const props = defineProps({
  treeData: {
    type: Array as PropType<TreeData[]>,
    default: () => []
  }
});

const handleTree = (value: TreeData[]) => {
  data.value = [];
  const treeData: TreeData = { value: 0, title: "顶级部门", children: [] };
  treeData.children = value;
  data.value.push(treeData);
};

watch(
  () => props.treeData,
  value => {
    handleTree(value);
  },
  {
    immediate: true
  }
);

const getBindValue = computed(() => {
  return {
    width: 560,
    title: "部门",
    model: form,
    saveApi: save,
    readApi: getEdit,
    labelWidth: '90px',
    bodyStyle: { padding: "20px" },
    rules: rules
  };
});
</script>

<template>
  <page-view>
    <s-table @register="register" @load-success="loadSuccess">
      <template #toolbar>
        <s-button icon="plus-outlined" type="primary" @click="openModal">新增</s-button>
      </template>
      <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'action'">
          <a @click="openModal(true, { parent_id: record.id })">添加</a>
          <a-divider type="vertical" />
          <a @click="openModal(true, record.id)">修改</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <department-form @register="registerModal" :treeData="treeData" @save-success="search" />
  </page-view>
</template>

<script setup lang="ts">
import { getDeptList, destroy } from "@/api/system/department";
import { treeEach } from "@/utils";
import departmentForm, { type TreeData } from "./form.vue";
type Key = string | number;
const defaultExpandedRowKeys = ref<Array<Key>>([]);
const treeData = ref<TreeData[]>([]);
const columns = [
  {
    title: "名称",
    dataIndex: "name",
    key: "name"
  },
  {
    title: "排序",
    dataIndex: "sort"
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const [register, { search, handleDelete }] = useTable({
  columns,
  listApi: getDeptList,
  deleteApi: destroy,
  defaultExpandedRowKeys: unref(defaultExpandedRowKeys),
  customExpandIcon: true,
  pagination: false
});

const [registerModal, { openModal }] = useModal();

const loadSuccess = (data: TreeData[]) => {
  treeEach(data, (item: TreeData) => {
    defaultExpandedRowKeys.value.push(item.id as Key);
  });
  treeData.value = data;
};
</script>

<template>
  <page-view>
    <s-table @register="register">
      <template #toolbar>
        <s-button type="primary" icon="plus-outlined" @click="openModal">添加</s-button>
      </template>
      <template #bodyCell="{ column, record }">
        <template v-if="column?.dataIndex === 'action' && record.id != 1">
          <router-link :to="{ path: `/system/auth/${record.id}`, query: { name: record.name } }"
            >权限</router-link
          >
          <a-divider type="vertical" />
          <a @click="openModal(true, record.id)">修改</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <role-form @register="registerModal" @save-success="search" />
  </page-view>
</template>

<script setup lang="ts">
import { getRoleList, destroy } from "@/api/system/role";
import roleForm from "./form.vue";

const searchForm = ref([
  {
    title: "关键词",
    dataIndex: "key",
    props: {
      placeholder: "请输入角色名称或权限标识搜索"
    }
  }
]);

const columns = [
  {
    title: "id",
    dataIndex: "id"
  },
  {
    title: "角色名称",
    dataIndex: "name"
  },
  {
    title: "权限标识",
    dataIndex: "role_key"
  },
  {
    title: "备注",
    dataIndex: "note"
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const [register, { search, handleDelete }] = useTable({
  columns,
  searchForm,
  listApi: getRoleList,
  deleteApi: destroy
});

const [registerModal, { openModal }] = useModal();
</script>

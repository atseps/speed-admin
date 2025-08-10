<template>
  <page-view>
    <s-table @register="register" v-model:expandedRowKeys="expandedRowKeys" @load-success="loadSuccess">
      <template #toolbar>
        <s-button icon="plus-outlined" type="primary" @click="openModal">新增</s-button>
        <s-button icon="down-square-outlined" @click="expandAll">展开</s-button>
        <s-button icon="up-square-outlined" @click="collapseAll">折叠</s-button>
      </template>
      <template #bodyCell="{ column, record }">
        <template v-if="column.dataIndex === 'action'">
          <a @click="openModal(true, { pid: record.id })">添加</a>
          <a-divider type="vertical" />
          <a @click="openModal(true, record)">修改</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <menu-form @register="registerModal" @save-success="search" :treeData="treeData"/>
  </page-view>
</template>

<script setup lang="tsx">
import { getMenuList, destroy } from "@/api/system/menu";
import menuForm from "./from.vue";
import { treeEach } from "@/utils";
import type { TableColumnProps } from "@/components/Table";
export interface TreeData {
  value: number;
  title: string;
  children: TreeData[];
}

const columns: TableColumnProps[] = [
  {
    title: "菜单名称",
    dataIndex: "title"
  },
  {
    title: "图标",
    dataIndex: "icon",
    customRender: ({ text }) => <s-icon type={text} />
  },
  {
    title: "路由地址",
    dataIndex: "path"
  },
  {
    title: "路由组件",
    dataIndex: "component"
  },
  {
    title: "菜单类型",
    dataIndex: "type_text",
    props: {
      type: "dict"
    }
  },
  {
    title: "权限节点",
    dataIndex: "rules"
  },
  {
    title: "菜单状态",
    dataIndex: "status",
    customRender: ({ value }) => {
      return value == "隐藏" ? <span class="text-red-500">{value}</span> : value;
    }
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

const expandedRowKeys = ref<Array<string>>([]);

const [register, { search, getDataSource, handleDelete }] = useTable({
  columns,
  listApi: getMenuList,
  deleteApi: destroy
});


const expandAll = () => {
  const data = getDataSource();
  expandedRowKeys.value = [];
  treeEach(data, (item: Recordable) => {
    expandedRowKeys.value.push(item.id);
  });
};

const treeData = ref<TreeData[]>([]);
const loadSuccess = (data: TreeData[]) => {
  treeData.value = [];
  const menu: TreeData = { value: 0, title: "顶级菜单", children: [] };
  menu.children = data;
  treeData.value.push(menu);
};

const collapseAll = () => {
  expandedRowKeys.value = [];
};

const [registerModal, { openModal }] = useModal();
</script>

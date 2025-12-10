<template>
  <page-view>
    <s-table
      @register="register"
      v-model:expandedRowKeys="expandedRowKeys"
      @load-success="loadSuccess"
    >
      <template
        #customFilterDropdown="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }"
      >
        <div style="padding: 8px">
          <a-input
            ref="searchInput"
            :placeholder="`搜索 ${column.title}`"
            :value="selectedKeys[0]"
            style="width: 188px; margin-bottom: 8px; display: block"
            @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
            @pressEnter="handleSearch(selectedKeys, confirm)"
          />
          <a-button
            type="primary"
            size="small"
            style="width: 90px; margin-right: 8px"
            @click="handleSearch(selectedKeys, confirm)"
          >
            <template #icon><SearchOutlined /></template> 搜索
          </a-button>
          <a-button size="small" style="width: 90px" @click="handleReset(clearFilters, confirm)">
            重置
          </a-button>
        </div>
      </template>

      <template #customFilterIcon="{ filtered }">
        <search-outlined :style="{ color: filtered ? '#1890ff' : undefined }" />
      </template>

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
    <menu-form @register="registerModal" @save-success="search" :treeData="treeData" />
  </page-view>
</template>

<script setup lang="tsx">
import { ref, h } from "vue";
import { SearchOutlined } from "@ant-design/icons-vue";
import { getMenuList, destroy } from "@/api/system/menu";
import menuForm from "./from.vue";
import { treeEach } from "@/utils";
import { useTreeSearch } from "./useTreeSearch";
import type { TableColumnProps } from "@/components/Table";
export interface TreeData {
  id: string | number;
  value: number;
  title: string;
  children?: TreeData[];
  [key: string]: any;
}

type Key = string | number;

const { searchText, expandedRowKeys, highlightText, doSearch, resetSearch } = useTreeSearch("title");
const treeData = ref<TreeData[]>([]);
const searchInput = ref();

const columns: TableColumnProps[] = [
  {
    title: "菜单名称",
    dataIndex: "title",
    key: "title",
    customFilterDropdown: true,
    onFilter: () => true,
    onFilterDropdownOpenChange: (visible: boolean) => {
      if (visible) {
        setTimeout(() => (searchInput.value as any)?.focus?.(), 100);
      }
    },
    customRender: ({ text }: { text: string }) => {
      const html = searchText.value ? highlightText(text, searchText.value) : text;
      return h("span", { innerHTML: html });
    }
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
    props: { type: "dict" }
  },
  {
    title: "权限节点",
    dataIndex: "rules"
  },
  {
    title: "菜单状态",
    dataIndex: "status",
    customRender: ({ value }: { value: string }) =>
      value === "隐藏" ? h("span", { class: "text-red-500" }, value) : value
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

const [register, { search, getDataSource, handleDelete }] = useTable({
  columns,
  listApi: getMenuList,
  deleteApi: destroy,
  pagination: false,
  rowKey: "id"
});

const loadSuccess = (data: TreeData[]) => {
  treeData.value = [];
  const root: TreeData = {
    id: "0",
    value: 0,
    title: "顶级菜单",
    children: data
  };
  treeData.value.push(root);
};

const expandAll = () => {
  const allIds: Key[] = [];
  treeEach(getDataSource(), (item: Recordable) => {
    if (item.id !== undefined) allIds.push(item.id);
  });
  expandedRowKeys.value = allIds;
};

const collapseAll = () => {
  expandedRowKeys.value = [];
};

// 搜索处理
const handleSearch = (selectedKeys: string[], confirm: () => void) => {
  confirm();
  doSearch(selectedKeys[0] || "", treeData);
};

// 重置处理
const handleReset = (clearFilters: () => void, confirm: () => void) => {
  clearFilters?.();
  resetSearch();
  confirm();
};

const [registerModal, { openModal }] = useModal();
</script>

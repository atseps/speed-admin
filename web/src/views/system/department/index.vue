<template>
  <page-view>
    <s-table
      @register="register"
      @load-success="loadSuccess"
      v-model:expandedRowKeys="expandedRowKeys"
    >
      <template #toolbar>
        <s-button icon="plus-outlined" type="primary" @click="openModal">新增</s-button>
      </template>
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
            <template #icon><SearchOutlined /></template>
            搜索
          </a-button>
          <a-button size="small" style="width: 90px" @click="handleReset(clearFilters, confirm)">
            重置
          </a-button>
        </div>
      </template>

      <template #customFilterIcon="{ filtered }">
        <search-outlined :style="{ color: filtered ? '#1890ff' : undefined }" />
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
import { SearchOutlined } from "@ant-design/icons-vue";
import { treeEach } from "@/utils";
import departmentForm, { type TreeData } from "./form.vue";
import { useTreeSearch } from "../menu/useTreeSearch";
type Key = string | number;

const defaultExpandedRowKeys = ref<Array<Key>>([]);
const searchInput = ref();
const treeData = ref<TreeData[]>([]);
const { searchText, expandedRowKeys, highlightText, doSearch, resetSearch } = useTreeSearch("name");
const columns = [
  {
    title: "名称",
    dataIndex: "name",
    key: "name",
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
</script>

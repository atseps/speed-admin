<template>
  <page-view>
    <s-table @register="register">
      <template #toolbar>
        <s-button type="primary" icon="plus-outlined" @click="openModal">导入数据表</s-button>
      </template>
      <template #bodyCell="{ column, record }">
        <template v-if="column?.dataIndex === 'action'">
          <a @click="handlePreview(record.id)">预览</a>
          <a-divider type="vertical" />
          <router-link :to="{ path: `/system/generator/${record.id}` }"> 配置 </router-link>
          <a-divider type="vertical" />
          <a @click="generatorCode(record.id)">生成代码</a>
          <a-divider type="vertical" />
          <s-confirm-button label="删除" title="确认要删除吗?" @confirm="handleDelete(record.id)" />
        </template>
      </template>
    </s-table>
    <data-table @register="registerModal" @save-success="search" />
    <code-preview v-if="showPreview" v-model="showPreview" :code="code" />
  </page-view>
</template>

<script setup lang="ts">
import { getList, destroy, preview } from "@/api/system/generator";
import { DataTable, CodePreview } from "./components";
import useGenerator from "./useGenerator";
const { generatorCode } = useGenerator();
const { message } = useMessage();
const code = ref([]);
const showPreview = ref(false);
const searchForm = ref([
  {
    title: "表名称",
    dataIndex: "table_name",
    props: {
      placeholder: "请输入表名称搜索"
    }
  },
  {
    title: "表描述",
    dataIndex: "table_comment",
    props: {
      placeholder: "请输入表描述搜索"
    }
  }
]);

const columns = [
  {
    title: "表名称",
    dataIndex: "table_name"
  },
  {
    title: "表描述",
    dataIndex: "table_comment"
  },
  {
    title: "创建时间",
    dataIndex: "create_time"
  },
  {
    title: "更新时间",
    dataIndex: "update_time"
  },
  {
    title: "操作",
    dataIndex: "action",
    width: 230
  }
];

const [register, { search, handleDelete }] = useTable({
  columns,
  searchForm,
  listApi: getList,
  deleteApi: destroy
});

const [registerModal, { openModal }] = useModal();


//预览
const handlePreview = async (id: string) => {
  const hide = message.loading({ content: "加载中", style: { marginTop: "40vh" } });
  const { data } = await preview(id);
  hide();
  code.value = data;
  setTimeout(() => {
    showPreview.value = true;
  }, 300);
};
</script>

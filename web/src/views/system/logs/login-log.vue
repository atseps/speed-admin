<template>
  <page-view>
    <s-table @register="register">
      <template #toolbar>
        <s-button type="primary" icon="delete-outlined" v-auth="'system:loginLog:clear'" @click="clear">清空</s-button>
        <s-button
          v-if="selectRowKeysLength"
          icon="delete-outlined"
          danger
          type="primary"
          @click="handleDelete"
          >批量删除</s-button
        >
        <s-export-data :api="exportData" />
      </template>
      <template #bodyCell="{ value, column }">
        <template v-if="column.dataIndex === 'os'">
          <s-icon v-if="value == 'Mac'" type="apple-filled" style="color: #aaa; font-size: 18px" />
          <s-icon v-else type="windows-filled" style="color: #40a9ff; font-size: 18px" />
          {{ value }}
        </template>
      </template>
    </s-table>
  </page-view>
</template>

<script setup lang="ts">
import { getLoginLogList, clear as clearApi, destroy, exportData } from "@/api/system/login_log";
const { createConfirm, responseNotice } = useMessage();
import type { TableColumnProps } from "@/components/Table/types";
const columns: TableColumnProps[] = [
  {
    title: "id",
    dataIndex: "id"
  },
  {
    title: "登录账号",
    dataIndex: "account",
    props: {
      type: "link"
    }
  },
  {
    title: "用户姓名",
    dataIndex: "realname"
  },
  {
    title: "登录IP",
    dataIndex: "login_ip"
  },
  {
    title: "操作系统",
    dataIndex: "os"
  },
  {
    title: "浏览器",
    dataIndex: "browser"
  },
  {
    title: "登录时间",
    dataIndex: "login_time"
  }
];

const searchForm = [
  {
    title: "登录账号",
    dataIndex: "account",
    props: {
      placeholder: "请输入登录账号搜索"
    }
  },
  {
    title: "用户姓名",
    dataIndex: "realname",
    props: {
      placeholder: "请输入用户姓名搜索"
    }
  },
  {
    title: "登录时间",
    dataIndex: "login_time",
    component: "RangePicker"
  }
];

const [register, { refresh, handleDelete, selectRowKeysLength }] = useTable({
  columns,
  searchForm,
  listApi: getLoginLogList,
  deleteApi: destroy,
  selection: true,
  debounceRender: false,
  showAction: false,
  canResize: true,

  searchSpan:6
});

const clear = () => {
  createConfirm({
    title: "确定清空全部日志吗?",
    onOk: async () => {
      const res = await clearApi();
      responseNotice(res);
      refresh();
    }
  });
};
</script>

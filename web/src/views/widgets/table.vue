<template>
  <page-view>
    <s-table @register="register">
      <template #toolbar>
        <s-button type="primary" icon="plus-outlined" @click="openDrawer">添加</s-button>
      </template>
      <template #bodyCell="{ value, column }">
        <template v-if="column.dataIndex === 'os'">
          <s-icon v-if="value == 'Mac'" type="apple-filled" style="color: #aaa; font-size: 18px" />
          <s-icon v-else type="windows-filled" style="color: #40a9ff; font-size: 18px" />
          {{ value }}
        </template>
      </template>
    </s-table>
    <Drawer @register="registerDrawer" />
  </page-view>
</template>

<script setup lang="ts">
import { getLoginLogList, destroy } from "@/api/system/login_log";
import Drawer from "./drawer.vue";
const [registerDrawer, { openDrawer }] = useDrawer();
const columns = [
  {
    title: "ID",
    dataIndex: "id"
  },
  {
    title: "登录账号",
    dataIndex: "account",
    columnsType: {
      type: "link"
    }
  },
  {
    title: "用户姓名",
    dataIndex: "realname",
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

const { options } = useDict(["device_level"]);

const searchForm = ref([
  {
    title: "登录账号",
    dataIndex: "account",
    props: {
      placeholder: "请输入登录账号搜索"
    }
  },
  {
    title: "登录时间",
    dataIndex: "login_time",
    component: "RangePicker"
  },
  {
    title: "类型",
    dataIndex: "login_type",
    component: "Select",
    props: {
      options: toRef(options, "device_level")
    }
  }
]);

const [register] = useTable({
  columns,
  searchForm,
  listApi: getLoginLogList,
  deleteApi: destroy,
  selection: true,
  showAction: false,
  canResize: true,
  showIndex: true
});
</script>

<template>
  <page-view>
    <a-card title="基础用法">
      <s-table-select
        v-model="value"
        placeholder="请选择"
        :api="getUserList"
        :columns="columns"
        labelField="realname"
      />
    </a-card>
    <a-card title="默认值" class="mt-[16px]">
      <s-table-select
        v-model="value"
        v-model:rows="defaultValue"
        :api="getUserList"
        :allowClear="true"
        placeholder="请选择"
        :columns="columns"
        labelField="realname"
      />
    </a-card>
    <a-card title="多选" class="mt-[16px]">
      <s-table-select
        multiple
        v-model="multipleVal"
        placeholder="请选择"
        :api="getUserList"
        :columns="columns"
        labelField="realname"
      />
    </a-card>
  </page-view>
</template>
<script setup lang="ts">
import { getUserList } from "@/api/system/user";
const value = ref("");
const multipleVal = ref("");
const columns = ref([
  {
    title: "ID",
    dataIndex: "id"
  },
  {
    title: "姓名",
    dataIndex: "realname"
  },
  {
    title: "用户名",
    dataIndex: "username"
  }
]);

const defaultValue = ref({ id: 22, realname: "张三" });
</script>

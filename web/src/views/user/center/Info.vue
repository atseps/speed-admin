<template>
  <a-list itemLayout="horizontal" :dataSource="data">
    <template #renderItem="{ item }">
      <a-list-item>
        <a-list-item-meta>
          <template #title>
            <a>{{ item.title }}</a>
          </template>
          <template #description>
            <span style="color: #828282">
              <span class="security-list-description">{{ item.description }}</span>
              <span v-if="item.value"> : </span>
              <span class="security-list-value m-l-10" v-if="item.title == '角色'">
                <a-tag v-for="value in item.value" :key="value">{{ value }}</a-tag>
              </span>
              <span v-else class="security-list-value">{{ item.value }}</span>
            </span>
          </template>
        </a-list-item-meta>
      </a-list-item>
    </template>
  </a-list>
</template>

<script setup lang="ts">
import { useUserStore } from "@/store/modules/user";
import { storeToRefs } from "pinia";
const store = useUserStore();
const { userInfo } = storeToRefs(store);
const data = ref([
  {
    title: "账号",
    description: "账号名称",
    value: unref(userInfo).username
  },
  {
    title: "角色",
    description: "所属角色",
    value: unref(userInfo).role_name
  },
  {
    title: "部门",
    description: "所属部门",
    value: unref(userInfo).department_name
  }
]);
</script>

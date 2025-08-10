<template>
  <a-config-provider :locale="zhCN" :theme="themeSetting">
    <token-provider>
      <router-view />
    </token-provider>
  </a-config-provider>
</template>

<script setup lang="ts">
import zhCN from "ant-design-vue/es/locale/zh_CN";
import "dayjs/locale/zh-cn";
import { useAppStore, setRealDarkTheme } from "@/store/modules/app";
import { storeToRefs } from "pinia";
const appStore = useAppStore();
setRealDarkTheme(appStore.theme);
const { themeSetting } = storeToRefs(appStore);
</script>

<template>
  <a-drawer
    v-if="isMobile"
    placement="left"
    :getContainer="undefined"
    :width="208"
    :closable="false"
    :class="`drawer-sider ant-theme-${navTheme}`"
    :open="sidebarOpened"
    @close="handleClose"
  >
    <Sider />
  </a-drawer>
  <Sider v-else />
</template>

<script setup lang="ts">
import Sider from "./Sider.vue";
import { useSetting } from "@/layout/hooks";
import { useAppStore } from "@/store/modules/app";
import { useRoute } from "vue-router";
const { sidebarOpened, isMobile, navTheme } = useSetting();
const store = useAppStore();
const route = useRoute();

watch(route, () => {
  unref(isMobile) && handleClose();
});

const handleClose = () => {
  store.changeSetting("sidebarOpened", false);
};
</script>

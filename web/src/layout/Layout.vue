<template>
  <a-layout :class="layoutClass">
    <layout-sidebar v-if="showSidebar" />
    <a-layout>
      <layout-header />
      <layout-tabs />
      <a-layout-content class="layout-content">
        <router-view v-slot="{ Component, route }">
          <transition v-if="openAnimation" :name="animation" mode="out-in" appear>
            <component :is="Component" :key="route.fullPath" />
          </transition>
          <component v-else :is="Component" :key="route.fullPath" />
        </router-view>
      </a-layout-content>
      <layout-footer v-if="showFooter" />
    </a-layout>
    <setting-drawer ref="settingDrawerRef" />
  </a-layout>
</template>

<script setup lang="ts">
import { LayoutSidebar, LayoutHeader, LayoutFooter, LayoutTabs, SettingDrawer } from "./components";
import { useDeviceEnquire, useSetting } from "./hooks";

onMounted(() => useDeviceEnquire());
const { showSidebar, theme, layout, animation, openAnimation, showMultiTabs, showFooter } = useSetting();

const settingDrawerRef = ref();
provide("openSetting", async () => {
  await nextTick();
  settingDrawerRef.value?.toggle();
});

const layoutClass = computed(() => {
  return [
    "basic-layout",
    `ant-theme-${unref(theme)}`,
    `ant-layout-${unref(layout)}`,
    { "multi-tabs": unref(showMultiTabs) }
  ];
});
</script>

<template>
  <div
    :style="{ width: `${stuffWidth}px`, flex: `0 0 ${stuffWidth}px` }"
    class="ant-fixed-stuff"
  ></div>
  <left-nav v-if="isLeftNotMobile" />
  <a-layout-sider
    v-if="sideMenus.length"
    :style="siderLeft"
    :width="sideWidth"
    :theme="navTheme"
    :collapsedWidth="sideWidth"
    :collapsed="!sidebarOpened"
    :collapsible="showSideTrigger"
    @collapse="toggle"
    class="ant-pro-sider-fixed ant-pro-sider"
  >
    <template #trigger>
      <div class="ant-pro-sider-links">
        <layout-trigger style="padding-left: 15px" />
      </div>
    </template>
    <Logo :showTitle="sidebarOpened" />
    <div style="flex: 1 1 0%; overflow: hidden auto">
      <base-menu :menus="sideMenus" :theme="navTheme" :collapsed="!sidebarOpened" />
    </div>
  </a-layout-sider>
</template>

<script setup lang="ts">
import BaseMenu from "../Menu/index";
import LeftNav from "./LeftNav.vue";
import { Logo } from "../Widget";
import { useSetting } from "@/layout/hooks";
import { LayoutTrigger } from "../Widget";
const {
  sideWidth,
  sidebarOpened,
  stuffWidth,
  navTheme,
  siderLeft,
  isLeftNotMobile,
  sideMenus,
  changeSetting,
  showSideTrigger
} = useSetting();

const toggle = () => {
  changeSetting("sidebarOpened", !unref(sidebarOpened));
};
</script>

<style lang="less" scoped>
.ant-pro-sider-fixed {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  overflow: auto;
  overflow-x: hidden;
  border-right: 1px solid var(--spe-layout-border-color);
}
.ant-pro-sider-links {
  text-align: left;
  border-top: 1px solid #f0f0f0;
}
:deep(.ant-layout-sider-trigger) {
  background: inherit;
  border-right: 1px solid var(--spe-layout-border-color);
}
.ant-pro-fixed-stuff {
  flex-shrink: 0;
  transition: width 0.2s;
}
.ant-pro-sider{
  :deep(.ant-menu-root){
    border-right:none;
  }
}
</style>

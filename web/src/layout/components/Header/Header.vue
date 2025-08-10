<template>
  <header :style="getDomStyle"></header>
  <a-layout-header
    :class="['layout-header', 'layout-header-fixed', `header-${headTheme}`]"
    :style="headerStyle"
  >
    <div class="ant-pro-global-header">
      <div class="flex items-center">
        <layout-trigger @click="toggle" v-if="sideOrMobile" />
        <s-breadcrumb v-if="showBreadcrumb" class="ml-[10px]" />
      </div>

      <div class="header-layout-menu" v-if="showTopMenu">
        <logo class="mr-[30px]" />
        <base-menu :theme="navTheme" :menus="menus" mode="horizontal" v-if="layout == 'top'" />
        <a-menu
          v-else-if="splitMenu"
          :selectedKeys="selectedKeys"
          :theme="headTheme"
          mode="horizontal"
          @click="routerTo"
        >
          <template v-for="item in menus">
            <a-menu-item v-if="!item.hidden" :key="item.path">
              <s-icon :type="item.meta.icon || 'appstore-outlined'" class="mr-[10px]"/>{{
                item.meta.title
              }}
            </a-menu-item>
          </template>
        </a-menu>
        <s-breadcrumb v-else class="flex items-center ml-[10px]" />
      </div>

      <div class="layout-header-action">
        <user-menu />
      </div>
    </div>
  </a-layout-header>
</template>

<script setup lang="ts">
import BaseMenu from "../Menu/index";
import { usePermissionStore } from "@/store/modules/permission";
import { useSetting, useHeaderSetting } from "@/layout/hooks";
import { UserMenu, LayoutTrigger, Logo } from "../Widget";
const { menus } = usePermissionStore();
const { width, height, showTopMenu, sideOrMobile, showBreadcrumb } = useHeaderSetting();
const { sidebarOpened, changeSetting, navTheme, layout, routerTo, splitMenu, headTheme } = useSetting();
const route = useRoute();

const selectedKeys = computed(() => [route.matched[0].path]);

const getDomStyle = computed(() => {
  return {
    height: unref(height),
    background: "transparent"
  };
});

const headerStyle = computed(() => {
  return {
    height: unref(height),
    lineHeight: unref(height),
    width: unref(width)
  };
});

const toggle = () => {
  changeSetting("sidebarOpened", !unref(sidebarOpened));
};
</script>

<style lang="less">
.basic-layout {
  .layout-header-fixed {
    position: fixed;
    top: 0;
    transition: width 0.2s;
  }

  .layout-header {
    padding: 0px;
    right: 0px;
    z-index: 9;
    .header-layout-menu {
      position: relative;
      width: 100%;
      height: 100%;
      transition: none;
      display: flex;
      transition: background 0.3s, width 0.2s;
      flex: 1;
      .ant-menu {
        background: transparent;
        line-height: inherit;
      }
    }

    .ant-pro-global-header {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 100%;
      padding: 0 16px;
      border-bottom: 1px solid var(--spe-layout-border-color);
    }
  }

  .header-light {
    background: #fff;
    .account-name {
      color: #808696;
    }
  }
}

.header-dark {
  .s-breadcrumb__inner,
  .s-breadcrumb__inner .no-redirect {
    color: #dadbdd;
  }
}
</style>

<template>
  <div :class="['left-nav-wrapper', `left-nav-${theme}`]">
    <logo style="display: block" :show-title="false" />
    <div class="left-nav-container">
      <template v-for="item in menus">
        <li
          v-if="!item.hidden"
          :class="[{ active: currentPath == item.path }, 'left-nav-item']"
          @click="routerTo(item)"
          :key="item.path"
        >
          <div class="left-nav-link">
            <a-tooltip :title="item.meta.title.length > 4 ? item.meta.title : ''" placement="right">
              <s-icon :size="16" :type="item.meta.icon || 'appstore-outlined'"></s-icon>
              <span class="line-feed-1 mt-[8px] ">{{ item.meta.title }}</span>
            </a-tooltip>
          </div>
        </li>
      </template>
    </div>
  </div>
</template>
<script setup lang="ts">
import { Logo } from "../Widget";
import { useSetting } from "@/layout/hooks";
const { menus, theme, routerTo } = useSetting();
const route = useRoute();
const currentPath = computed(() => route.matched[0].path);
</script>
<style lang="less" scoped>
@dark-bg-color: #0c131b;
.left-nav-wrapper {
  :deep(.logo) {
    background: @dark-bg-color;
  }
  width: 80px;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  z-index: 99;
  overflow: hidden;
}

.left-nav-container {
  overflow: auto;
  overflow-x: hidden;
  width: 100%;
  height: 100vh;
  padding: 0 8px;
  flex-shrink: 0;
  background: @dark-bg-color;
  .left-nav-item {
    padding: 8px 3px;
    margin-bottom: 10px;
    cursor: pointer;
    width: 100%;
    color: #fff;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    &.active {
      background: var(--ant-color-primary);
    }

    &:not(.active):hover {
      background: rgba(255, 255, 255, 0.1);
    }
  }

  .left-nav-link {
    display: flex;
    width: 100%;
    height: 100%;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
}

//暗黑模式
.left-nav-realDark {
  .left-nav-container,
  :deep(.logo) {
    background: #0c131b;
  }
}

//亮色主题
.left-nav-light {
  border-right: 1px solid #f0f0f0;

  :deep(.logo),
  .left-nav-container {
    background: #fff;
  }
  .left-nav-item {
    position: relative;
    color: #606266;
    &.active {
      color: var(--ant-color-primary);
      background: var(--ant-color-primary-bg);
    }

    &:hover {
      color: var(--ant-color-primary);
    }
  }
}
</style>

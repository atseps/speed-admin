<template>
  <div class="trigger-container">
    <s-icon class="trigger" :type="sidebarOpened ? 'menu-fold-outlined' : 'menu-unfold-outlined'" />
  </div>
</template>

<script lang="ts" setup>
import { useSetting } from "@/layout/hooks";
const { sidebarOpened } = useSetting();
</script>

<style lang="less" scoped>
.trigger-container {
  padding: 0 8px;
  cursor: pointer;
}

.trigger {
  position: relative;
  top: 2px;
  font-size: 18px;
}
.header-dark .trigger {
  color: #fff;
}
</style>

<template>
  <a-tooltip placement="top" v-model:open="open">
    <template #title>
      <span>全屏</span>
    </template>
    <s-icon
      :type="isFullscreen ? 'fullscreen-exit-outlined' : 'fullscreen-outlined'"
      @click="toggle"
      :size="13"
    />
  </a-tooltip>
</template>
<script lang="ts" setup>
import { useTableContext } from "../../hooks";
const { wrapRef, redoHeight } = useTableContext();
const isFullscreen = ref(false);
const toggle = () => {
  isFullscreen.value = !isFullscreen.value;
};
const open = ref(false);
watch(isFullscreen, value => {
  if (value) {
    unref(wrapRef)?.classList.add("fullscreen-table");
  } else {
    unref(wrapRef)?.classList.remove("fullscreen-table");
  }
  open.value = false;
  redoHeight!();
});
</script>
<style lang="less">
.fullscreen-table {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  overflow: auto;
  padding: 0 0 16px;
  box-sizing: border-box;
  background-color: var(--ant-color-bg-container);
  z-index: 999;
}
</style>

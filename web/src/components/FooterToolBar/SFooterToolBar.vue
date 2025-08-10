<template>
  <div
    class="s-footer-toolbar"
    :class="{ centered: centered }"
    :style="{ width: `calc(100% - ${reduceWidth}px)` }"
  >
    <div style="float: left">
      <slot name="extra">{{ extra }}</slot>
    </div>
    <div style="float: right">
      <slot></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useSetting } from "@/layout/hooks";
defineProps({
  extra: {
    type: [String, Object],
    default: ""
  },
  centered: {
    type: Boolean,
    default: false
  }
});
const { reduceWidth } = useSetting();
</script>
<style lang="less" scoped>
.s-footer-toolbar {
  position: fixed;
  width: 100%;
  bottom: 0;
  right: 0;
  height: 56px;
  line-height: 56px;
  box-shadow: 0 -1px 2px rgba(0, 0, 0, 0.03);
  background: var(--ant-color-bg-container);
  border-top: 1px solid var(--ant-color-border-secondary);
  padding: 0 24px;
  z-index: 9;

  &:after {
    content: "";
    display: block;
    clear: both;
  }
}

.centered {
  display: flex;
  justify-content: center;
}
</style>

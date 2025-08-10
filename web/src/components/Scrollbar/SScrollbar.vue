<template>
  <ScrollContainer ref="scrollbarRef" class="scroll-container" v-bind="$attrs">
    <slot></slot>
  </ScrollContainer>
</template>

<script setup lang="ts">
import { ref, unref, nextTick } from "vue";
import ScrollContainer from "./ScrollContainer.vue";
import { ScrollbarType } from "./types.d";
import { useScrollTo } from "@/hooks/useScrollTo";

const scrollbarRef = ref<Nullable<ScrollbarType>>(null);

/**
 * Scroll to the specified position
 */
function scrollTo(to: number, duration = 500) {
  const scrollbar = unref(scrollbarRef);
  if (!scrollbar) {
    return;
  }
  nextTick(() => {
    const wrap = unref(scrollbar.wrap);
    if (!wrap) {
      return;
    }
    const { start } = useScrollTo({
      el: wrap,
      to,
      duration
    });
    start();
  });
}

function getScrollWrap() {
  const scrollbar = unref(scrollbarRef);
  if (!scrollbar) {
    return null;
  }
  return scrollbar.wrap;
}

/**
 * Scroll to the bottom
 */
function scrollBottom() {
  const scrollbar = unref(scrollbarRef);
  if (!scrollbar) {
    return;
  }
  nextTick(() => {
    const wrap = unref(scrollbar.wrap);
    if (!wrap) {
      return;
    }
    const scrollHeight = wrap.scrollHeight as number;
    const { start } = useScrollTo({
      el: wrap,
      to: scrollHeight
    });
    start();
  });
}

defineExpose({
  scrollTo,
  scrollBottom,
  getScrollWrap
});
</script>
<style lang="less">
.scroll-container {
  width: 100%;
  height: 100%;

  .scrollbar__wrap {
    margin-bottom: 18px;
    overflow-x: hidden;
  }

  .scrollbar__view {
    box-sizing: border-box;
  }
}
</style>

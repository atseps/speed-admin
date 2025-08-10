<template>
  <section
    class="full-loading"
    :class="{ absolute, [theme]: !!theme }"
    :style="[background ? `background-color: ${background}` : '']"
    v-show="loading"
  >
    <a-spin v-bind="$attrs" :tip="tip" :size="size" :spinning="loading" />
  </section>
</template>
<script lang="ts" setup>
import { SizeEnum } from "./typing";
defineProps({
  tip: {
    type: String as PropType<string>,
    default: ""
  },
  size: {
    type: String as PropType<SizeEnum>,
    default: SizeEnum.DEFAULF,
    validator: (v: SizeEnum): boolean => {
      return [SizeEnum.DEFAULT, SizeEnum.SMALL, SizeEnum.LARGE, SizeEnum.DEFAULF].includes(v);
    }
  },
  absolute: {
    type: Boolean as PropType<boolean>,
    default: false
  },
  loading: {
    type: Boolean as PropType<boolean>,
    default: false
  },
  background: {
    type: String as PropType<string>
  },
  theme: {
    type: String as PropType<any>
  }
});
</script>
<style lang="less" scoped>
@modal-mask-bg: rgba(0, 0, 0, 0.1);

.full-loading {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 200;
  display: flex;
  width: 100%;
  height: 100%;
  justify-content: center;
  align-items: center;
  background-color: #f8f8f866;

  &.absolute {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 300;
  }
}

[data-theme="dark"] {
  .full-loading:not(.light) {
    background-color: @modal-mask-bg;
  }
}

.full-loading.dark {
  background-color: @modal-mask-bg;
}
</style>

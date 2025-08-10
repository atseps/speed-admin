<template>
  <div class="basic-drawer-footer" :style="getStyle" v-if="showFooter || $slots.footer">
    <template v-if="!$slots.footer">
      <slot name="insertFooter"></slot>
      <a-button v-bind="cancelButtonProps" @click="handleClose" class="mr-2" v-if="showCancelBtn">
        {{ cancelText }}
      </a-button>
      <slot name="centerFooter"></slot>
      <a-button
        :type="okType"
        @click="handleOk"
        v-bind="okButtonProps"
        class="mr-2"
        :loading="confirmLoading"
        v-if="showOkBtn"
      >
        {{ okText }}
      </a-button>
      <slot name="appendFooter"></slot>
    </template>

    <template v-else>
      <slot name="footer"></slot>
    </template>
  </div>
</template>

<script setup lang="ts">
import type { CSSProperties } from "vue";
import { computed } from "vue";
import { footerProps } from "../props";

const props = defineProps({
  ...footerProps,
  height: {
    type: String,
    default: "60px"
  }
});

const emit = defineEmits(["ok", "close"]);

const getStyle = computed((): CSSProperties => {
  const heightStr = `${props.height}`;
  return {
    height: heightStr,
    lineHeight: `calc(${heightStr} - 1px)`
  };
});

function handleOk() {
  emit("ok");
}

function handleClose() {
  emit("close");
}
</script>

<style lang="less" scoped>
.basic-drawer-footer {
  position: absolute;
  bottom: 0;
  width: 100%;
  padding: 0 12px 0 20px;
  text-align: right;
  background-color: var(--ant-color-bg-container);
  border-top: 1px solid var(--ant-color-border);

  > * {
    margin-right: 8px;
  }
}
</style>

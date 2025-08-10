<template>
  <div :class="getClass">
    <template v-if="canFullscreen">
      <s-icon
        type="fullscreen-exit-outlined"
        v-if="fullScreen"
        role="full"
        @click="handleFullScreen"
      />

      <s-icon type="fullscreen-outlined" v-else role="full" @click="handleFullScreen" />
    </template>
    <s-icon class="close-icon" type="close-outlined" role="full" @click="handleCancel" />
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";

type Props = {
  canFullscreen?: boolean;
  fullScreen?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
  canFullscreen: true,
  fullScreen: undefined
});

// Emits 定义
const emit = defineEmits<{
  (e: "cancel", event: Event): void;
  (e: "fullscreen", event: Event): void;
}>();

const prefixCls = "basic-modal-close";

const getClass = computed(() => {
  return [
    prefixCls,
    `${prefixCls}--custom`,
    {
      [`${prefixCls}--can-full`]: props.canFullscreen
    }
  ];
});

function handleCancel(e: Event) {
  emit("cancel", e);
}

function handleFullScreen(e: Event) {
  e?.stopPropagation();
  e?.preventDefault();
  emit("fullscreen", e);
}
</script>

<style lang="less">
.ant-modal-close:focus,
.ant-modal-close:hover {
  color: rgba(0, 0, 0, 0.45);
}
.basic-modal-close {
  display: flex;
  height: 95%;
  align-items: center;

  > span {
    margin-left: 48px;
    font-size: 16px;
  }

  &--can-full {
    > span {
      margin-left: 12px;
    }
  }

  &:not(&--can-full) {
    > span:nth-child(1) {
      &:hover {
        font-weight: 700;
      }
    }
  }

  & span:nth-child(1) {
    display: inline-block;
    padding: 4px;
    border-radius: 4px;
    &:hover {
      color: rgba(0, 0, 0, 0.88);
      background-color: rgba(0, 0, 0, 0.06);
    }
    &:active {
      background-color: rgba(0, 0, 0, 0.15);
    }
  }
  .close-icon {
    padding: 4px;
    border-radius: 4px;
    &:hover {
      color: rgba(0, 0, 0, 0.88);
      background-color: rgba(0, 0, 0, 0.06);
    }
    &:active {
      background-color: rgba(0, 0, 0, 0.15);
    }
  }
}
html[data-theme="dark"] {
  .ant-modal-close:focus,
  .ant-modal-close:hover {
    color: #7e7e7e;
  }
  .basic-modal-close {
    & span:nth-child(1):hover {
      color: #fff;
    }
    & .close-icon:hover {
      color: #fff;
    }
  }
}
</style>

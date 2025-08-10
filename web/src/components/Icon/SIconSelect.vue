<template>
  <a-input
    readonly
    :style="{ width }"
    placeholder="请选择图标"
    class="s-icon-select"
    v-model:value="currentSelect"
    allow-clear
  >
    <template #prefix>
      <s-icon v-if="currentSelect" :type="currentSelect"  class="cursor-pointer px-2 py-1" />
      <s-icon
        @click="onClose"
        v-if="currentSelect"
        class="cursor-pointer close-icon"
        type="close-circle-filled"
      ></s-icon>
    </template>
    <template #addonAfter>
      <span
        class="cursor-pointer px-2 py-1 text-xs text-gray-500 select-btn"
        @click="setVisible(true)"
        >选择</span
      >
    </template>
  </a-input>

  <s-modal title="选择图标" :width="800" :footer="null" v-model:visible="visible" centered>
    <icon-selector v-model:value="currentSelect" />
  </s-modal>
</template>
<script lang="ts" setup>
import { propTypes } from "@/utils/propTypes";
import IconSelector from "./components/IconSelector.vue";
const props = defineProps({
  modelValue: propTypes.string,
  width: propTypes.string.def("100%")
});

const emit = defineEmits(["change", "update:modelValue"]);

const currentSelect = ref("");
const visible = ref(false);

const setVisible = (val: boolean) => {
  visible.value = val;
};

const onClose = () => {
  currentSelect.value = "";
};

watchEffect(() => {
  currentSelect.value = props.modelValue;
});

watch(
  () => currentSelect.value,
  v => {
    visible.value = false;
    emit("update:modelValue", v);
    emit("change", v);
  }
);
</script>
<style lang="less" scoped>
.s-icon-select {
  :deep(.ant-input-affix-wrapper:hover) {
    .close-icon {
      display: block;
    }
  }
  :deep(.ant-input-group-addon) {
    padding: 0;
  }
  .close-icon {
    position: absolute;
    right: 10px;
    z-index: 99;
    display: none;
    cursor: pointer;
    color: rgba(0, 0, 0, 0.25);
    &:hover {
      color: rgba(0, 0, 0, 0.45);
    }
  }
}
</style>

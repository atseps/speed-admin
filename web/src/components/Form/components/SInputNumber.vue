<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-input-number class="w-full" v-bind="inputNumberPropsVal" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
    </a-input-number>
  </a-form-item>
</template>

<script setup lang="ts">
import { omit, keys } from "lodash-es";
import type { InputNumberProps } from "ant-design-vue";
import type { FormItemProps } from "ant-design-vue";
import { inputNumberProps } from "ant-design-vue/lib/input-number";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

const props = defineProps({
  ...inputNumberProps(),
  ...formItemProps(),
  modelValue: {
    type: [String, Number]
  }
});

const emit = defineEmits(["update:modelValue"]);

const value = useVModel(props, "modelValue", emit);

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, keys(inputNumberProps())), name: props.name };
});

const exclude = [...keys(formItemProps()), "modelValue"];

const { customSlots: formItemSlots, componentSlots } = useWidgetSlots(["label", "extra", "help"]);

const inputNumberPropsVal = computed((): InputNumberProps => {
  return { ...omit(props, exclude) } as InputNumberProps;
});
</script>

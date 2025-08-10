<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-textarea v-bind="textPropsVal" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
    </a-textarea>
  </a-form-item>
</template>

<script setup lang="ts">
import { omit, keys } from "lodash-es";
import { textAreaProps } from "ant-design-vue/lib/input/inputProps";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import type { TextAreaProps, FormItemProps } from "ant-design-vue";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

const props = defineProps({
  ...textAreaProps(),
  ...formItemProps(),
  modelValue: {
    type: [String, Number]
  }
});

const emit = defineEmits(["update:modelValue"]);

const value = useVModel(props, "modelValue", emit);

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, keys(textAreaProps())), name: props.name };
});

const exclude = [...keys(formItemProps()), "modelValue"];

const textPropsVal = computed((): TextAreaProps => {
  return { ...omit(props, exclude) } as TextAreaProps;
});

const { customSlots: formItemSlots, componentSlots } = useWidgetSlots(["label", "extra", "help"]);
</script>

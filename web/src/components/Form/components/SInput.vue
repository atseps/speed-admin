<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-input v-bind="inputPropsVal" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
    </a-input>
  </a-form-item>
</template>

<script setup lang="ts">
import { omit, keys } from "lodash-es";
import inputProps from "ant-design-vue/lib/input/inputProps";
import type { InputProps } from "ant-design-vue";
import type { FormItemProps } from "ant-design-vue";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

const props = defineProps({
  ...inputProps(),
  ...formItemProps(),
  modelValue: {
    type: [String, Number]
  }
});

const emit = defineEmits(["update:modelValue"]);

const value = useVModel(props, "modelValue", emit);

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, keys(inputProps())), name: props.name };
});

const exclude = [...keys(formItemProps()), "modelValue"];

const inputPropsVal = computed((): InputProps => {
  return { ...omit(props, exclude) } as InputProps;
});

const { customSlots: formItemSlots, componentSlots } = useWidgetSlots(["label", "extra", "help"]);
</script>

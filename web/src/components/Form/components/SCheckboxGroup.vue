<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-checkbox-group v-bind="checkboxPropsVal" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
    </a-checkbox-group>
  </a-form-item>
</template>

<script lang="ts" setup name="ApiSelect">
import { omit, keys } from "lodash-es";
import type { CheckboxGroupProps } from "ant-design-vue";
import type { FormItemProps } from "ant-design-vue";
import { checkboxGroupProps } from "ant-design-vue/lib/checkbox";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import { apiProps } from "./props";
import useOptions from "./useOptions";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

type CheckboxValue = NonNullable<CheckboxGroupProps["value"]>;

const props = defineProps({
  ...checkboxGroupProps(),
  ...formItemProps(),
  ...apiProps,
  modelValue: {
    type: [Array, String] as PropType<CheckboxValue>
  }
});

const emit = defineEmits(["update:modelValue", "options-change"]);

const value = useVModel(props, "modelValue", emit);

const excludeKey = [...keys(formItemProps()), ...keys(apiProps)];

const excludeItemKey = [...keys(checkboxGroupProps()), ...keys(apiProps)];

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, excludeItemKey), name: props.name };
});

const {
  customSlots: formItemSlots,
  componentSlots,
  slots
} = useWidgetSlots(["label", "extra", "help"]);

const checkboxPropsVal = computed((): CheckboxGroupProps => {
  return {
    ...omit(props, excludeKey),
    options: slots.default ? undefined : unref(getOptions)
  } as CheckboxGroupProps;
});
const { getOptions } = useOptions(props, emit);
</script>

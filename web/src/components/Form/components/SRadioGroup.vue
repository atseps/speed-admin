<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-radio-group v-bind="radioGroupPropsVal" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
    </a-radio-group>
  </a-form-item>
</template>

<script lang="ts" setup>
import { omit, keys } from "lodash-es";
import type { RadioGroupProps } from "ant-design-vue";
import type { FormItemProps } from "ant-design-vue";
import { radioGroupProps } from "ant-design-vue/lib/Radio/Group";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import { apiProps } from "./props";
import useOptions from "./useOptions";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

const props = defineProps({
  ...radioGroupProps(),
  ...formItemProps(),
  ...apiProps
});

const emit = defineEmits(["update:modelValue", "options-change"]);

const value = useVModel(props, "modelValue", emit);

const excludeKey = [...keys(formItemProps()), ...keys(apiProps)];

const excludeItemKey = [...keys(radioGroupProps()), ...keys(apiProps)];

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, excludeItemKey), name: props.name };
});

const { getOptions } = useOptions(props, emit);

const {
  customSlots: formItemSlots,
  componentSlots,
  slots
} = useWidgetSlots(["label", "extra", "help"]);

const radioGroupPropsVal = computed((): RadioGroupProps => {
  return {
    ...omit(props, excludeKey),
    options: slots.default ? undefined : unref(getOptions)
  } as RadioGroupProps;
});
</script>

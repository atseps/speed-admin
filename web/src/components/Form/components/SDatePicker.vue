<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-date-picker v-bind="$attrs" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
    </a-date-picker>
  </a-form-item>
</template>

<script setup lang="ts">
import { omit } from "lodash-es";
import type { FormItemProps } from "ant-design-vue";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import type { DatePickerProps } from "ant-design-vue";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

type DateValue = NonNullable<DatePickerProps["value"]>;

const props = defineProps({
  ...formItemProps(),
  modelValue: {
    type: [Object, String] as PropType<DateValue>
  }
});

const emit = defineEmits(["update:modelValue"]);

const value = useVModel(props, "modelValue", emit);

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, ["modelValue"]), name: props.name };
});

const { customSlots: formItemSlots, componentSlots } = useWidgetSlots(["label", "extra", "help"]);
</script>

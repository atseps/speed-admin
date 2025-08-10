<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-select @dropdownVisibleChange="handleFetch" v-bind="selectPropsVal" v-model:value="value">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
      <template #suffixIcon v-if="loading">
        <s-icon type="loading-outlined" spin />
      </template>
      <template #notFoundContent v-if="loading">
        <span>
          <s-icon type="loading-outlined" spin class="mr-1" />
          请等待数据加载完成...
        </span>
      </template>
    </a-select>
  </a-form-item>
</template>

<script setup lang="ts">
import { omit, keys } from "lodash-es";
import { selectProps } from "ant-design-vue/lib/select";
import { formItemProps } from "ant-design-vue/lib/form";
import { useVModel } from "@vueuse/core";
import { apiProps } from "./props";
import useOptions from "./useOptions";
import type { SelectProps } from "ant-design-vue";
import type { FormItemProps } from "ant-design-vue";
import type { Ref } from "vue";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

const props = defineProps({
  ...selectProps(),
  ...formItemProps(),
  ...apiProps
});

const emit = defineEmits(["update:modelValue", "options-change"]);

const { getOptions, handleFetch } = useOptions(props, emit);

const value = useVModel(props, "modelValue", emit) as Ref<SelectProps["value"]>;

const excludeKey = [...keys(formItemProps()), ...keys(apiProps)];

const excludeItemKey = [...keys(selectProps()), ...keys(apiProps)];

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, excludeItemKey), name: props.name };
});

const {
  customSlots: formItemSlots,
  componentSlots,
  slots
} = useWidgetSlots(["label", "extra", "help"]);

const selectPropsVal = computed((): SelectProps => {
  return {
    ...omit(props, excludeKey),
    options: slots.default ? undefined : unref(getOptions),
  };
});
</script>

<template>
  <a-form-item v-bind="formItemPropsVal">
    <template #[item] v-for="item in formItemSlots" :key="item">
      <slot :name="item"></slot>
    </template>
    <a-tree-select v-bind="getProps" v-model:value="value" @dropdownVisibleChange="handleFetch">
      <template #[item]="value" v-for="item in componentSlots" :key="item">
        <slot :name="item" v-bind="value || {}"></slot>
      </template>
      <template #suffixIcon v-if="loading">
        <s-icon type="loading-outlined" spin />
      </template>
    </a-tree-select>
  </a-form-item>
</template>

<script lang="ts" setup>
import { isFunction } from "@/utils/is";
import { get, omit, keys, isEqual } from "lodash-es";
import { formItemProps } from "ant-design-vue/lib/form";
import { apiTreeSelectProps } from "./props";
import { useVModel } from "@vueuse/core";
import type { FormItemProps, TreeSelectProps } from "ant-design-vue";
import type { Ref } from "vue";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";


const props = defineProps({
  ...formItemProps(),
  ...apiTreeSelectProps
});

const emit = defineEmits(["update:modelValue", "options-change"]);

const value = useVModel(props, "modelValue", emit) as Ref<TreeSelectProps["value"]>;

const formItemPropsVal = computed((): FormItemProps => {
  return { ...omit(props, keys(apiTreeSelectProps)), name: props.name };
});

const treeData = ref<Recordable[]>([]);
const loading = ref(false);
const isFirstLoaded = ref(false);
const attrs = useAttrs() as Recordable;

const { customSlots: formItemSlots, componentSlots } = useWidgetSlots(["label", "extra", "help"]);

const getProps = computed(():TreeSelectProps => {
  return {
    ...(props.api ? { treeData: unref(treeData) } : {}),
    dropdownStyle: { maxHeight: "400px", overflow: "auto" },
    treeNodeFilterProp: props.labelField,
    showSearch: true,
    fieldNames: {
      value: props.valueField,
      label: props.labelField
    },
    ...attrs
  };
});

const fetch = async () => {
  const api = props.api;
  if (!api || !isFunction(api)) return;
  treeData.value = [];
  try {
    loading.value = true;
    isFirstLoaded.value = true;
    const res = await api(props.params);
    if (Array.isArray(res)) {
      treeData.value = res;
      emitChange();
      return;
    }
    if (props.resultField) {
      treeData.value = get(res, props.resultField) || [];
      emitChange();
    }
  } catch (error) {
    isFirstLoaded.value = false;
    console.warn(error);
  } finally {
    loading.value = false;
  }
};

watch(
  () => props.params,
  (value, oldValue) => {
    if (isEqual(value, oldValue)) return;
    fetch();
  },
  { deep: true, immediate: props.immediate }
);

const handleFetch = async (visible: boolean) => {
  if (visible) {
    if (props.alwaysLoad) {
      await fetch();
    } else if (!props.immediate && !unref(isFirstLoaded)) {
      await fetch();
    }
  }
};

const emitChange = () => {
  emit("options-change", unref(treeData));
};
</script>

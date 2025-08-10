<template>
  <a-form-item-rest>
    <a-input-group compact style="display: flex">
      <a-select v-model:value="selectValue" :options="options" />
      <slot v-if="$slots.default" :field="selectValue"></slot>
      <a-range-picker
        v-else-if="type == 'date'"
        :presets="presets"
        style="width: 100%"
        v-model:value="modelValue[selectValue]"
      />
      <a-input
        v-else-if="type == 'input'"
        :placeholder="getPlaceholder"
        style="width: 100%"
        v-model:value="modelValue[selectValue]"
      />
    </a-input-group>
  </a-form-item-rest>
</template>

<script setup lang="ts">
import type { OptionType, PresetType } from "./types";

const props = defineProps({
  defaultField: {
    type: String,
    required: true
  },
  placeholder: {
    type: String,
    default: "请输入"
  },
  options: {
    type: Array as PropType<OptionType[]>,
    required: true
  },
  type: {
    type: String as PropType<"date" | "input">,
    default: "date"
  },
  model: {
    type: Object,
    required: true
  },
  presets: {
    type: Array as PropType<PresetType[]>,
    default: () => []
  }
});

const selectValue = ref(props.defaultField);

const modelValue = ref(props.model);

watch(
  () => props.model,
  value => {
    modelValue.value = value;
  },
  {
    deep: true,
    immediate: true
  }
);

const getPlaceholder = computed(() => {
  const { options, type } = props;
  if (type == "input") {
    const prlaceholder = options?.find((item: OptionType) => item.value == selectValue.value)?.placeholder;
    return prlaceholder ? prlaceholder : props.placeholder;
  }
  return props.placeholder;
});

watch(selectValue, (value, oldValue) => {
  const newModel = { ...props.model };
  newModel[oldValue] = undefined;
  Object.assign(props.model, newModel);
});
</script>

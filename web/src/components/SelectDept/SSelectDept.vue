<template>
  <a-tree-select
    allowClear
    v-bind="getTreeProps"
    :showCheckedStrategy="SHOW_ALL"
    :value="modelValue"
    @change="handleChange"
    :maxTagCount="maxTagCount"
    :dropdownStyle="{ maxHeight: `${maxHeight}px`, overflow: 'auto' }"
  />
</template>
<script setup lang="ts">
import { getDeptList } from "@/api/system/department";
import { omit } from "lodash-es";
import { TreeSelect, type TreeSelectProps } from "ant-design-vue";
const SHOW_ALL = TreeSelect.SHOW_ALL;

const treeData = ref<TreeSelectProps["treeData"]>([]);

const props = defineProps({
  modelValue: [String, Number, Array],
  treeCheckable: {
    type: Boolean,
    default: false
  },
  maxTagCount: {
    type: Number,
    default: 3
  },
  maxHeight: {
    type: Number,
    default:400
  },
  treeData: {
    type: Array as PropType<TreeSelectProps["treeData"]>
  }
});

const attrs = useAttrs();

const getTreeProps = computed(() => {
  let propsData: Recordable = {
    ...attrs,
    ...props
  };
  propsData.treeData = (props.treeData && props.treeData.length > 0) ? props.treeData : treeData.value;
  propsData = omit(propsData, "class");
  return propsData;
});

const emit = defineEmits(["update:modelValue"]);

onMounted(() => {
  getData();
});

const getData = async () => {
  if (!props.treeData) {
    const res = await getDeptList();
    treeData.value = res.data;
  }
};
function handleChange(value: string | number | (string | number)[]) {
  emit("update:modelValue", value);
}
</script>

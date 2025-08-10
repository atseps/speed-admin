<template>
  <QueryFilter
    v-bind="getSearchFormProps"
    :class="className"
    @submit="onSubmit"
    @reset="onReset"
    :key="key"
    :labelCol="{ flex: labelWidth }"
    :model="state.model"
  >
    <template v-if="searchForm && searchForm.length > 0">
      <SearchFormItem
        v-for="item in searchForm"
        :model="state.model"
        v-bind="item"
        :key="item.dataIndex"
      >
        <template #[item]="data" v-for="item in componentSlots">
          <slot :name="item" v-bind="data || {}"></slot>
        </template>
      </SearchFormItem>
    </template>
  </QueryFilter>
</template>

<script lang="ts" setup>
import { QueryFilter } from "@/components/QueryFilter";
import { queryFilterProps } from "@/components/QueryFilter/types";
import { propTypes } from "@/utils/propTypes";
import { omit } from "lodash-es";
import { Form } from "ant-design-vue";
import SearchFormItem from "./components/SearchFormItem";
import type { SearchFormType } from "./typing";
import { clearObject } from "@/utils";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";
const { componentSlots } = useWidgetSlots();
const useForm = Form.useForm;

const key = ref(0);

const props = defineProps({
  ...queryFilterProps,
  searchForm: {
    type: Array as PropType<SearchFormType[]>,
    default: () => []
  },
  labelWidth: propTypes.string.def("80px")
});

// watch(
//   () => props.searchForm
//   () => {
//     //doSearchLayout()
//   },
//   {
//     deep: true
//   }
// );

const doSearchLayout = () => {
  key.value++;
};

const state = reactive({
  model: {} as Recordable
});

useForm(state.model);

const className = computed(() => {
  const searchFormBtn = props.searchForm && props.searchForm.length < 3 ? "search-form-btn" : "";
  return ["search-form", searchFormBtn];
});

const getSearchFormProps = computed(() => {
  return { ...omit(props, ["onSubmit", "onReset", "searchForm", "labelWidth"]) };
});

const emit = defineEmits(["submit", "reset", "search"]);

const onSubmit = () => {
  emit("submit", state.model);
  emit("search", state.model);
};

const getFormValue = () => {
  return state.model;
};

const onReset = () => {
  clearObject(state.model);
  emit("reset", state.model);
};

defineExpose({ getFormValue, doSearchLayout });
</script>

<style lang="less" scoped>
.search-form {
  :deep(.ant-form-item-label) {
    padding-right: 8px;
  }

  :deep(.ant-form-item) {
    margin-bottom: 8px;
  }

  :deep(.ant-form-item-control) {
    flex: 1;
  }
  :deep(.ant-col) {
    height: 45px;
  }

  :deep(.ant-form-item-label) {
    width: v-bind(labelWidth);
  }
  :deep(.ant-row .ant-col:last-child) {
    margin-left: 0;
  }
}
</style>

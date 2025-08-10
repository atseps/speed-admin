<template>
  <div :class="['s-table', { 'render-antd-card': renderCard }]" ref="wrapRef">
    <s-search-form
      v-if="searchFormData && searchFormData.length > 0"
      :searchForm="searchFormData"
      @reset="onReset"
      @search="onSearch"
      :labelWidth="getProps.labelWidth"
      @collapsed="redoHeight"
      :span="getProps.searchSpan"
      ref="searchFormRef"
      :defaultColsNumber="getProps.defaultColsNumber"
    >

      <template #[replaceFormSlotKey(item)]="data" v-for="item in getFormSlotKeys">
        <slot :name="item" v-bind="data || {}"></slot>
      </template>
    </s-search-form>
    <template v-else>
      <slot name="search"></slot>
    </template>
    <div class="s-table-wrapper" :style="rootStyleRef">
      <div class="s-table-header" v-if="showTableHeader">
        <table-header :showTableSetting="getProps.showTableSetting">
          <template #[item]="data" v-for="item in getSearchFormSlots" :key="item">
            <slot :name="item" v-bind="data || {}"></slot>
          </template>
        </table-header>
      </div>
      <a-table ref="tableElRef" v-bind="getTableProps" @change="handleTableChange">
        <template #[item]="data" v-for="item in componentSlots" :key="item">
          <slot :name="item" v-bind="data || {}"></slot>
        </template>
      </a-table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { basicProps } from "./props";
import {
  useDataSource,
  usePagination,
  useLoading,
  useColumns,
  useRowSelection,
  useTableScroll,
  createTableContext
} from "./hooks";
import { isFunction } from "@/utils/is";
import { omit, pick } from "lodash-es";
import { clearObject } from "@/utils";
import expandIcon from "./components/ExpandIcon";
import TableHeader from "./components/TableHeader.vue";
import type { BasicTableProps, TableActionType, SizeType } from "./types";
import type { SearchFormType } from "../SearchForm";
import type { ComputedRef, Slots } from "vue";

const props = defineProps({
  ...basicProps,
  rootStyle: {
    type: Object
  }
});

const renderCard = inject("renderCard", false);

const rootStyleRef = computed(() => {
  return {
    padding: unref(renderCard) ? 0 : "16px",
    ...(unref(getProps).rootStyle || {})
  };
});

const attrs = useAttrs();
const slots = useSlots() as Slots;
const emit = defineEmits(["load-success", "load-error", "change", "register", "reset", "search"]);

//内部的props, 用于useTable传的参数进行合并到props中
const innerProps = ref<Partial<BasicTableProps>>();

//表格ref
const tableElRef = ref();
//搜索表单ref
const searchFormRef = ref();
//容器的ref
const wrapRef = ref();

const getProps = computed(() => {
  return { ...props, ...unref(innerProps) } as BasicTableProps;
});

const setProps = (props: Partial<BasicTableProps>) => {
  innerProps.value = { ...unref(innerProps), ...props };
};

const getFormValue = () => {
  return searchFormRef.value?.getFormValue();
};

const doSearchLayout = () => {
  return searchFormRef.value?.doSearchLayout();
};

const { setLoading, getLoading } = useLoading(getProps);

const { getPagination, setPagination, setPageVisible } = usePagination(getProps);

const { getRowSelection, clearSelectedRowKeys, getSelectRows, getSelectRowKeys } =
  useRowSelection(getProps);

const { tableData, handleChange, refresh, search, searchState, getDataSource } = useDataSource(
  getProps,
  {
    getPagination,
    setPagination,
    setPageVisible,
    setLoading,
    clearSelectedRowKeys,
    getFormValue
  },
  emit
);

const { getColumns, setColumns, getViewColumns } = useColumns(getProps, getPagination, searchState);

const getSearchFormSlots = computed<string[]>(() => {
  return Object.keys(pick(slots, ["headerTop", "toolbar"]));
});

const componentSlots = computed<string[]>(() => Object.keys(slots));

const showTableHeader = computed(() => {
  const existSlots = Object.keys(slots).some(item => ["headerTop", "toolbar"].includes(item));
  if (unref(getProps).showTableSetting || existSlots) {
    return true;
  }
  return false;
});

const getExpandIcon = computed(() => {
  if (unref(getProps).expandIcon) {
    return unref(getProps).expandIcon;
  }

  if (unref(getProps).customExpandIcon) {
    return expandIcon();
  }

  return undefined;
});

//自适应表格高度
const { getScroll, redoHeight } = useTableScroll(getProps, tableElRef, tableData, getColumns);

//绑定的表格属性
const getTableProps = computed(() => {
  let propsData: Recordable = {
    ...attrs,
    expandIcon: unref(getExpandIcon),
    ...unref(getProps),
    loading: unref(getLoading),
    scroll: unref(getScroll),
    rowSelection: unref(getRowSelection),
    columns: toRaw(unref(getViewColumns)),
    pagination: unref(getPagination),
    dataSource: unref(tableData),
    onResizeColumn: (w: number, col: Recordable) => {
      col.width = w;
    }
  };
  propsData = omit(propsData, "class");
  return propsData;
});

//搜索表单重置
const onReset = (data: Recordable) => {
  clearSliderValue(data);
  nextTick(() => {
    clearObject(data);
    refresh(data);
    emit("reset", data);
  });
};

//搜索表单搜索
const onSearch = (data: Recordable) => {
  refresh(data, false);
  emit("search", data);
};

const searchFormData = computed(() => {
  const search = unref(getProps).searchForm;
  return unref(search) as SearchFormType[] | undefined;
});

//清除Slider组件的值
const clearSliderValue = (data: Recordable) => {
  const sliderData = unref(searchFormData)?.filter(
    (item: Recordable) => item.component == "Slider"
  );
  sliderData?.forEach((item: Recordable) => {
    const { range } = item.props;
    if (range) {
      data[item.dataIndex] = [0, 0];
    } else {
      data[item.dataIndex] = 0;
    }
  });
};

const handleTableChange = (...args: any[]) => {
  handleChange.call(undefined, ...(args as Parameters<typeof handleChange>));
  emit("change", ...args);
  // 解决通过useTable注册onChange时不起作用的问题
  const { onChange } = unref(getProps);
  onChange &&
    isFunction(onChange) &&
    onChange.call(undefined, ...(args as Parameters<typeof onChange>));
};

const getFormSlotKeys: ComputedRef<string[]> = computed(() => {
  const keys = Object.keys(slots);
  return keys
    .map(item => (item.startsWith("search-") ? item : null))
    .filter(item => !!item) as string[];
});

function replaceFormSlotKey(key: string) {
  if (!key) return "";
  return key?.replace?.(/search-/, "") ?? "";
}

const tableAction: Omit<TableActionType, "handleDelete" | "handleExport"> = {
  refresh,
  search,
  clearSelectedRowKeys,
  getSelectRows,
  getSelectRowKeys,
  setProps,
  getDataSource,
  redoHeight,
  getColumns,
  setColumns,
  getFormValue,
  doSearchLayout,
  getSize: () => {
    return unref(getTableProps).size as SizeType;
  }
};

const elRef = {
  tableElRef,
  wrapRef
};

createTableContext({ ...tableAction, ...elRef, getTableProps });

defineExpose({ tableElRef, ...tableAction });

emit("register", tableAction);
</script>

<style lang="less" scoped>
.s-table .hide-scrollbar-y {
  :deep(.ant-table-body) {
    overflow-y: auto !important;
  }
}

.s-table-header {
  margin-bottom: 16px;
  background: var(--ant-color-bg-container);
}

.s-table-wrapper {
  background: var(--ant-color-bg-container);
  border-radius: var(--ant-border-radius);
}

.s-table:not(.render-antd-card) {
  .search-form {
    background: var(--ant-color-bg-container);
    padding: 12px 12px 0;
    border-radius: var(--ant-border-radius);
    margin-bottom: 10px;
  }
}
</style>

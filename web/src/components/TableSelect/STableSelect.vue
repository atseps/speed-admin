<template>
  <div class="table-select">
    <a-popover trigger="click" placement="bottom" :open="visible" @openChange="visibleChange">
      <template #content>
        <div class="table-container" :style="{ width: `${state.dropdownWidth + 10}px` }">
          <div class="search mb-2.5 d-flex">
            <a-form-item-rest>
              <a-input-search
                style="width: 200px"
                placeholder="请输入关键词搜索"
                v-model:value="searchValue"
                @search="onSearch"
                class="input-search"
              />
            </a-form-item-rest>
            <s-button @click="onReset" class="reset" icon="reload-outlined" />
          </div>
          <a-table
            size="small"
            :columns="columns"
            :rowKey="valueField"
            :customRow="customRow"
            :pagination="pagination"
            :data-source="tableData"
            :loading="loading"
            @change="handleChange"
            :rowSelection="rowSelection"
            :scroll="{ x: state.dropdownWidth, y: 300 }"
            rowClassName="table-select-row"
          />
        </div>
      </template>
      <div
        class="table-select-input"
        @mouseover="onShowClearIcon"
        ref="selectContainerRef"
        @mouseleave="showClearIcon = false"
      >
        <div :class="[disabled ? 'is_disabled' : 'is_valid']" class="select-container">
          <div
            v-for="(item, index) in selectValueList"
            :key="item[valueField]"
            :class="multiple ? 'select-item' : 'select-radio-item'"
          >
            {{ item[labelField] }}
            <s-icon
              type="close-outlined"
              v-if="multiple"
              class="select-remove-icon"
              @click.stop="remove(item[valueField], index)"
            />
          </div>
          <span class="select-item" v-if="showMoreNumber"> + {{ moreNumber }} ...</span>
          <div class="add-item placeholder" v-show="!selectValueList.length">
            {{ placeholder }}
          </div>
          <div class="add-item select-arrow-icon" v-if="!multiple">
            <s-icon v-if="showClearIcon" @click.stop="onClear" type="close-circle-filled" />
            <s-icon v-else :style="iconStyle" type="down-outlined" />
          </div>
        </div>
      </div>
    </a-popover>
  </div>
</template>

<script setup lang="ts">
import request from "@/utils/request";
import { useList } from "./useList";
import { isString, isEmpty, isObject } from "@/utils/is";
import type { TableProps } from "ant-design-vue";
import { ComputedRef } from "vue";
import { isEqual } from "lodash-es";
import { Form } from "ant-design-vue";
const formItemContext = Form.useInjectFormItemContext();
type Key = string | number;
type apiFn = (params: Record<string, any>) => Promise<any>;
const props = defineProps({
  value: {
    type: [String, Number]
  },
  width: {
    type: Number,
    default: 600
  },
  dropdownMatchSelectWidth: {
    type: Boolean,
    default: false
  },
  queryParam: {
    type: Object as PropType<Recordable>,
    default: () => ({})
  },
  columns: {
    type: Array as PropType<TableProps["columns"]>,
    default: () => []
  },
  disabled: {
    type: Boolean,
    default: false
  },
  labelField: {
    type: String,
    default: "name"
  },
  valueField: {
    type: String,
    default: "id"
  },
  rows: {
    type: [Array, Object] as PropType<Recordable | Recordable[]>,
    default: () => []
  },
  placeholder: {
    type: String,
    default: ""
  },
  multiple: {
    type: Boolean,
    default: false
  },
  api: {
    type: [Function, String] as PropType<apiFn | string>,
    required: true
  },
  allowClear: {
    type: Boolean,
    default: true
  },
  maxTagCount: {
    type: Number,
    default: undefined
  },
  immediate: {
    //是否初始化加载
    type: Boolean,
    default: true
  },
  alwaysLoad: {
    //每次下拉都加载数据
    type: Boolean,
    default: false
  }
});

const state = reactive({
  visible: false,
  searchValue: undefined,
  selectValue: [] as Recordable[],
  selectedRows: [] as Recordable[],
  selectedRowKeys: [] as Key[],
  showClearIcon: false,
  dropdownWidth: 0
});
const selectContainerRef = ref();

const { visible, searchValue, showClearIcon } = toRefs(state);

const maxTagList = computed(() => {
  return state.selectValue.slice(0, props.maxTagCount);
});

const selectValueList = computed(() => {
  if (props.multiple && props.maxTagCount) {
    return unref(maxTagList);
  }
  return state.selectValue;
});

const moreNumber = computed(() => {
  return state.selectValue.length - unref(maxTagList).length;
});

const showMoreNumber = computed(() => {
  return props.multiple && props.maxTagCount && unref(moreNumber) > 0;
});

const emit = defineEmits(["update:value", "update:rows", "change"]);

const requestFn = (params: Recordable) => {
  return request.get(props.api as string, { params });
};

const listApi = computed(() => {
  if (isString(props.api)) {
    return requestFn;
  }
  return props.api as apiFn;
});

const iconStyle = computed(() => {
  return {
    transition: "transform 0.3s",
    fontSize: "12px"
  };
});

watch(
  () => props.width,
  value => {
    if (!props.dropdownMatchSelectWidth) {
      state.dropdownWidth = value;
    }
  },
  {
    immediate: true
  }
);

watch(
  () => visible.value,
  val => {
    if (props.alwaysLoad && val) {
      loadData();
    }
  }
);

const setMatchSelectWidth = () => {
  if (props.dropdownMatchSelectWidth) {
    state.dropdownWidth = unref(selectContainerRef).offsetWidth - 30;
  }
};

const handleSelectChange = () => {
  const { labelField, valueField } = props;
  var currentValue: Recordable = {};
  if (props.multiple) {
    state.selectValue = state.selectedRows.map((item: Recordable) => {
      return { [valueField]: item[valueField], [labelField]: item[labelField] };
    });
    state.selectedRowKeys = state.selectedRows.map((item: Recordable) => item[valueField]);
  } else {
    currentValue = state.selectedRows.pop() || {};
    if (!isEmpty(currentValue)) {
      state.selectValue = [
        { [valueField]: currentValue[valueField], [labelField]: currentValue[labelField] }
      ];
      state.selectedRowKeys = [currentValue[valueField]];
      state.visible = false;
    } else {
      state.selectValue = [];
      state.selectedRowKeys = [];
    }
  }
  const value = props.multiple ? state.selectedRowKeys?.toString() : currentValue[valueField];
  emit("update:value", value);
  emit("change", value);
  emit("update:rows", props.multiple ? state.selectValue : state.selectValue[0]);
  formItemContext.onFieldChange();
};

const rowSelection: ComputedRef<TableProps["rowSelection"]> = computed(() => {
  return {
    selectedRowKeys: state.selectedRowKeys,
    hideSelectAll: true,
    onChange: (selectedRowKeys: Key[], selectedRows: Recordable[]) => {
      state.selectedRowKeys = selectedRowKeys;
      state.selectedRows = selectedRows;
    },
    async onSelect() {
      await nextTick();
      handleSelectChange();
    },
    type: props.multiple ? "checkbox" : "radio"
  };
});

const onShowClearIcon = () => {
  if (!isEmpty(state.selectValue) && props.allowClear) {
    showClearIcon.value = true;
  }
};

//清除数据
const clearData = () => {
  state.selectedRows = [];
  state.selectedRowKeys = [];
  state.selectValue = [];
  emit("update:value", "");
  emit("update:rows", []);
};

const onClear = () => {
  clearData();
  state.visible = false;
  emit("change", "");
  formItemContext.onFieldChange();
};

const selectRow = (record: Recordable) => {
  const { valueField } = props;
  const selectedRowKeys = [...state.selectedRowKeys];
  if (selectedRowKeys.includes(record[valueField])) {
    selectedRowKeys.splice(selectedRowKeys.indexOf(record[valueField]), 1);
    state.selectedRows = state.selectedRows.filter(
      (item: Recordable) => item[valueField] != record[valueField]
    );
  } else {
    selectedRowKeys.push(record[valueField]);
    state.selectedRows.push(record);
  }
  state.selectedRowKeys = selectedRowKeys;
  handleSelectChange();
};

const customRow = (record: Recordable) => {
  return {
    onClick: (event:MouseEvent) => {
      // 阻止复选框点击触发行点击
      const target = event.target as HTMLElement;
      if (target.closest('.ant-checkbox-wrapper, .ant-checkbox')) {
        return;
      }
      selectRow(record);
    }
  };
};

const searchParams = computed(() => {
  return {
    ...props.queryParam,
    key: searchValue.value
  };
});

const { setPageCurrent, pagination, tableData, loadData, handleChange, loading } = useList(
  unref(listApi),
  searchParams
);

onMounted(() => {
  props.immediate && loadData();
  setMatchSelectWidth();
});

const refresh = () => {
  setPageCurrent(1);
  loadData();
};

const onSearch = () => {
  refresh();
};

const onReset = () => {
  state.searchValue = undefined;
  refresh();
};

const remove = (value: Key, index: number) => {
  const { valueField } = props;
  state.selectValue.splice(index, 1);
  state.selectedRowKeys = state.selectedRowKeys.filter(item => item != value);
  state.selectedRows = state.selectedRows.filter((item: Recordable) => item[valueField] != value);
  handleSelectChange();
};

watch(
  () => props.value,
  value => {
    if (!value) {
      clearData();
    }
  },
  {
    immediate: true
  }
);

onBeforeUnmount(() => {
  clearData();
});

watch(
  () => props.rows,
  value => {
    if (isEmpty(value) || !value) return;
    const selectedData = props.multiple ? state.selectValue : state.selectValue[0];
    if (isEqual(selectedData, value)) return;
    if (isObject(value)) value = [value];
    state.selectedRowKeys = value.map((item: Recordable) => item[props.valueField]);
    state.selectValue = value as Recordable[];
    state.selectedRows = value as Recordable[];
  },
  {
    deep: true,
    immediate: true
  }
);

const visibleChange = (visible: boolean) => {
  if (props.disabled) {
    state.visible = false;
    return;
  }
  state.visible = visible;
};
</script>

<style lang="less" scoped>
.select-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  min-height: 32px;
  position: relative;
  border-radius: var(--ant-border-radius);
  font-size: 12px;
  border: 1px solid var(--ant-color-border);
  padding: 0.5px;
  line-height: 15px;
  max-height: 105px;
  overflow-y: auto;
  cursor: pointer;
  .select-radio-item {
    padding: 3px;
    margin: 3px;
    font-size: 14px;
    padding-inline-start: 8px;
  }
  .select-item {
    padding: 3px;
    background-color: #f5f5f5;
    border-radius: var(--ant-color-border);
    margin: 3px;
    padding-inline-start: 8px;
    cursor: pointer;
    border: 1px solid #f0f0f0;
    border-radius: var(--ant-border-radius);
  }

  .select-remove-icon {
    color: inherit;
    font-style: normal;
    line-height: 0;
    text-align: center;
    text-transform: none;
    vertical-align: -0.125em;
    text-rendering: optimizeLegibility;
    color: rgba(0, 0, 0, 0.45);
    font-weight: 700;
    line-height: inherit;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-block;
    font-size: 12px;
    transform: scale(0.83333333) rotate(0deg);
  }

  .add-item {
    padding: 5px;
    color: #bfbfbf;
    font-size: 14px;
    cursor: pointer;
  }

  .placeholder {
    width: 80%;
    overflow: hidden;
    padding: 4px 11px;
    position: absolute;
    white-space: nowrap;
    text-align: left;
    text-overflow: ellipsis;
    color: var(--ant-color-text-quaternary);
  }

  .select-arrow-icon {
    position: absolute;
    top: 50%;
    right: 8px;
    margin-top: -12px;
  }
}

.table-container {
  :deep(.ant-table-wrapper) {
    padding: 0px;
  }
}

.is_disabled {
  color: rgba(0, 0, 0, 0.25);
  background-color: #f5f5f5;
  cursor: not-allowed;
  opacity: 1;
  pointer-events: none;

  .add-item {
    color: #9a9a9a;
    cursor: not-allowed;
  }

  .select-item {
    cursor: not-allowed;
  }
}

.reset {
  padding: 5px;
  margin-left: 10px;
  font-size: 12px;
}

:deep(.table-select-row) {
  cursor: pointer;
}
.ant-form-item-has-error {
  .table-select .select-container {
    border: 1px solid #ff4d4f;
  }
}
.table-container .input-search :deep(.ant-input-wrapper .ant-input) {
  border-color: var(--ant-color-border);
}
[data-theme="dark"] {
  .select-container {
    border: 1px solid var(--ant-color-border);
  }

  .select-item {
    background: var(--ant-color-bg-container);
    color: var(--ant-color-text-quaternary);
    border: 1px solid var(--ant-color-border);
  }
}
</style>

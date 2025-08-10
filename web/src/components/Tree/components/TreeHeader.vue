<template>
  <div class="flex mb-1 px-2 py-1.5 items-center tree-header">
    <slot name="headerTitle" v-if="slots.headerTitle"></slot>
    <template v-if="!slots.headerTitle && title">
      {{ title }}
    </template>
    <div
      class="flex items-center flex-1 cursor-pointer justify-self-stretch"
      v-if="search || toolbar"
    >
      <div :class="getInputSearchCls" v-if="search">
        <a-input-search
          placeholder="请输入内容搜索"
          size="small"
          allowClear
          v-model:value="searchValue"
        />
      </div>
      <a-dropdown @click.prevent v-if="toolbar">
        <s-icon type="more-outlined" />
        <template #overlay>
          <a-menu @click="handleMenuClick">
            <template v-for="item in toolbarList" :key="item.value">
              <a-menu-item v-bind="{ key: item.value }">
                {{ item.label }}
              </a-menu-item>
              <a-menu-divider v-if="item.divider" />
            </template>
          </a-menu>
        </template>
      </a-dropdown>
    </div>
  </div>
</template>
<script lang="ts" setup>
import { useDebounceFn } from "@vueuse/core";
import { ToolbarEnum } from "../types/tree";
import type { MenuProps } from "ant-design-vue";
const searchValue = ref("");

const props = defineProps({
  title: {
    type: String,
    default: ""
  },
  toolbar: {
    type: Boolean,
    default: false
  },
  checkable: {
    type: Boolean,
    default: false
  },
  search: {
    type: Boolean,
    default: false
  },
  searchText: {
    type: String,
    default: ""
  },
  checkAll: {
    type: Function,
    default: undefined
  },
  expandAll: {
    type: Function,
    default: undefined
  }
} as const);
const emit = defineEmits(["strictly-change", "search"]);
const slots = useSlots();

const getInputSearchCls = computed(() => {
  const titleExists = slots.headerTitle || props.title;
  return [
    "mr-1",
    "w-full",
    {
      ["ml-5"]: titleExists
    }
  ];
});

const toolbarList = computed(() => {
  const { checkable } = props;
  const defaultToolbarList = [
    { label: "展开所有", value: ToolbarEnum.EXPAND_ALL },
    {
      label: "折叠所有",
      value: ToolbarEnum.UN_EXPAND_ALL,
      divider: checkable
    }
  ];

  return checkable
    ? [
        { label: "选择全部", value: ToolbarEnum.SELECT_ALL },
        {
          label: "取消选择",
          value: ToolbarEnum.UN_SELECT_ALL,
          divider: checkable
        },
        ...defaultToolbarList,
        { label: "层级关联", value: ToolbarEnum.CHECK_STRICTLY },
        { label: "层级独立", value: ToolbarEnum.CHECK_UN_STRICTLY }
      ]
    : defaultToolbarList;
});

const handleMenuClick: MenuProps["onClick"] = e => {
  const { key } = e;
  switch (key) {
    case ToolbarEnum.SELECT_ALL:
      props.checkAll?.(true);
      break;
    case ToolbarEnum.UN_SELECT_ALL:
      props.checkAll?.(false);
      break;
    case ToolbarEnum.EXPAND_ALL:
      props.expandAll?.(true);
      break;
    case ToolbarEnum.UN_EXPAND_ALL:
      props.expandAll?.(false);
      break;
    case ToolbarEnum.CHECK_STRICTLY:
      emit("strictly-change", false);
      break;
    case ToolbarEnum.CHECK_UN_STRICTLY:
      emit("strictly-change", true);
      break;
  }
};

function emitChange(value?: string): void {
  emit("search", value);
}

const debounceEmitChange = useDebounceFn(emitChange, 200);

watch(
  () => searchValue.value,
  v => {
    debounceEmitChange(v);
  }
);

watch(
  () => props.searchText,
  v => {
    if (v !== searchValue.value) {
      searchValue.value = v;
    }
  }
);
</script>

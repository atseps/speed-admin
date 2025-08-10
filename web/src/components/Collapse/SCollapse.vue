<template>
  <a-collapse
    class="s-collapse"
    :bordered="bordered"
    expandIconPosition="end"
    v-loading="loading"
    v-model:activeKey="activeKey"
  >
    <a-collapse-panel key="key" :header="title" :showArrow="canExpand" :collapsible="collapsible">
      <slot></slot>
    </a-collapse-panel>
  </a-collapse>
</template>
<script lang="ts" setup>
type Key = string | number;
const props = defineProps({
  bordered: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean
  },
  title: {
    type: String,
    default: ""
  },
  canExpand: {
    type: Boolean,
    default: true
  },
  defaultExpand: {
    type: Boolean,
    default: true
  }
});

const activeKey = ref<Key[]>([]);

watch(
  () => props.defaultExpand,
  val => {
    activeKey.value = val ? ["key"] : [];
  },
  { immediate: true }
);

const collapsible = computed(() => {
  return props.canExpand ? undefined : "disabled";
});
</script>
<style lang="less" scoped>
.s-collapse {
  background: var(--ant-color-bg-container);

  :deep(.ant-collapse-header) {
    border-bottom: 1px solid var(--ant-color-border-secondary);
    padding: 10px 12px;
    font-weight: 500;
  }

  :deep(.ant-collapse-item) {
    border-bottom: none;
  }

  :deep(.ant-collapse-item > .ant-collapse-content .ant-collapse-content-box) {
    padding: 8px;
  }

  :deep(.ant-collapse-item-disabled > .ant-collapse-header) {
    color: var(--ant-color-text);
    cursor: auto;
  }
}
</style>

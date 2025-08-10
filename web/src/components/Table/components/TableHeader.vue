<template>
  <div class="table-header-container">
    <div v-if="$slots.headerTop">
      <slot name="headerTop"></slot>
    </div>
    <div class="flex items-center" v-if="showTableSetting || $slots.toolbar">
      <div class="table-header-toolbar">
        <div>
          <slot name="toolbar"></slot>
        </div>
        <TableSetting v-if="showTableSetting" class="table-header__toolbar-desktop" />
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import TableSetting from "./settings/index.vue";
defineProps({
  showTableSetting: {
    type: Boolean
  }
});
</script>
<style lang="less">
.table-header-container {
  width: 100%;
  padding: 0;
}

.table-header-toolbar {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;

  div > * {
    margin-right: 8px;
  }
}
</style>

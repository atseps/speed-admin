<template>
  <a-tooltip placement="top">
    <template #title>
      <span>刷新</span>
    </template>
    <s-icon type="redo-outlined" @click="refresh" :size="13" />
  </a-tooltip>
</template>
<script lang="ts" setup>
import { useTableContext } from "../../hooks";
const table = useTableContext();
const refresh = () => {
  table.refresh();
};
</script>

<template>
  <a-tooltip placement="top">
    <template #title>
      <span>密度</span>
    </template>

    <a-dropdown placement="bottom" :trigger="['click']">
      <s-icon type="column-height-outlined" :size="13" />
      <template #overlay>
        <a-menu @click="handleTitleClick" selectable v-model:selectedKeys="selectedKeysRef">
          <a-menu-item key="default">
            <span>默认</span>
          </a-menu-item>
          <a-menu-item key="middle">
            <span>中等</span>
          </a-menu-item>
          <a-menu-item key="small">
            <span>紧凑</span>
          </a-menu-item>
        </a-menu>
      </template>
    </a-dropdown>
  </a-tooltip>
</template>
<script lang="ts" setup>
import { useTableContext } from "../../hooks";
import type { SizeType } from "../../types";
import type { MenuInfo } from "ant-design-vue/es/menu/src/interface";
import type { Key } from "ant-design-vue/es/table/interface";

const table = useTableContext();
const selectedKeysRef = ref<Key[]>([table.getSize() as Key]);

function handleTitleClick(info: MenuInfo) {
  const key = info.key as SizeType;
  selectedKeysRef.value = [key as Key];
  table.setProps({
    size: key
  });
  table.redoHeight()
}
</script>

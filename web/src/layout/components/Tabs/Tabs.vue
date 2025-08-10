<template>
  <div
    class="multi-tab-stuff"
    :style="`height: ${height}`"
    v-if="fixedMultiTabs && showMultiTabs"
  ></div>
  <div
    :style="{ width: tabsWidth, top: fixedMultiTabs ? height : '' }"
    :class="['tabs', tabsType, { 'multi-tab-fixed': fixedMultiTabs }]"
    v-if="showMultiTabs"
  >
    <a-tabs
      hide-add
      v-model:activeKey="active"
      @change="onChange"
      @edit="onEdit"
      class="tab"
      type="editable-card"
    >
      <a-tab-pane v-for="item in list" :key="item.path" :closable="list.length > 1">
        <template #tab>
          <s-icon :type="item.icon || 'appstore-outlined'" />
          {{ item.title }}
        </template>
      </a-tab-pane>
      <template #rightExtra>
        <a-dropdown class="tab-tool">
          <a-button style="border: none">
            <template #icon>
              <s-icon type="DownOutlined" />
            </template>
          </a-button>
          <template #overlay>
            <a-menu>
              <a-menu-item @click="closeOther"> 关 闭 其 他 </a-menu-item>
              <a-menu-item @click="closeCurrent"> 关 闭 当 前 </a-menu-item>
              <a-menu-item @click="closeAll"> 关 闭 全 部 </a-menu-item>
            </a-menu>
          </template>
        </a-dropdown>
      </template>
    </a-tabs>
  </div>
</template>

<script setup lang="ts">
import { useTabs } from "./useTabs";
import { useSetting, useHeaderSetting } from "@/layout/hooks";
const { showMultiTabs, fixedMultiTabs, tabsWidth, tabsType } = useSetting();
const { navigation, list, active, close, closeOther, closeCurrent, closeAll } = useTabs();
const { height } = useHeaderSetting();
import { TabsProps } from "ant-design-vue";
const onEdit: TabsProps["onEdit"] = (path, action) => {
  if (action == "remove") close(path as string);
};

const onChange: TabsProps["onChange"] = path => {
  const routes = list.value.find(item => item.path == path);
  navigation({ path: path as string, query: routes?.query });
};
</script>

<style lang="less">
@import "./index.less";
</style>

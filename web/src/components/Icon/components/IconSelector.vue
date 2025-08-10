<template>
  <div :class="prefixCls">
    <a-tabs v-model:activeKey="currentTab" @change="handleTabChange">
      <a-tab-pane v-for="v in icons" :tab="v.title" :key="v.key">
        <ul class="icon-list">
          <li
            class="icon-item"
            v-for="(icon, key) in v.icons"
            :key="`${v.key}-${key}`"
            :class="{ active: selectedIcon === icon }"
            @click="handleSelectedIcon(icon)"
          >
            <s-icon :type="icon" :style="{ fontSize: '22px' }" />
          </li>
        </ul>
      </a-tab-pane>
    </a-tabs>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import type { Ref } from "vue";
import icons from "./icons";

interface IconGroup {
  key: string;
  title: string;
  icons: string[];
}

interface Props {
  prefixCls?: string;
  value?: string;
}

const props = withDefaults(defineProps<Props>(), {
  prefixCls: "s-icon-selector",
  value: ""
});

const emit = defineEmits<{
  (e: "change", icon: string): void;
  (e: "update:value", icon: string): void;
}>();

const selectedIcon: Ref<string> = ref(props.value || "");
const currentTab: Ref<string> = ref("directional");

const handleSelectedIcon = (icon: string): void => {
  selectedIcon.value = icon;
  emit("change", icon);
  emit("update:value", icon);
};

const handleTabChange = (activeKey: string | number): void => {
  currentTab.value = activeKey.toString();
};

const autoSwitchTab = (): void => {
  icons.some(
    (item: IconGroup) =>
      item.icons.some((icon: string) => icon === props.value) && (currentTab.value = item.key)
  );
};

// 监听器
watch(
  () => props.value,
  (val: string) => {
    selectedIcon.value = val || "";
    autoSwitchTab();
  }
);

// 生命周期
onMounted(() => {
  if (props.value) {
    autoSwitchTab();
  }
});
</script>

<style lang="less" scoped>
.icon-list {
  list-style: none;
  padding: 0;
  overflow-y: scroll;
  height: 320px;

  .icon-item {
    display: inline-block;
    padding: 12px;
    margin: 3px 0;
    border-radius: 2px;

    &:hover,
    &.active {
      cursor: pointer;
      color: #fff;
      background-color: var(--ant-color-primary);
    }
  }
}
</style>

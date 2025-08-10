<template>
  <div class="setting-container">
    <a-drawer
      width="310"
      placement="right"
      @close="onClose"
      :closable="false"
      :rootClassName="rootClassName"
      :bodyStyle="{ padding: '20px' }"
      :open="visible"
    >
      <div v-if="setPosition == 'header'" class="custom-close-btn flex-center" @click="onClose">
        <s-icon type="close-outlined" />
      </div>

      <div class="setting-drawer-index-content">
        <div :style="{ marginBottom: '24px' }">
          <h3 class="setting-drawer-index-title">整体风格设置</h3>
          <div class="setting-drawer-block-checbox">
            <a-tooltip v-for="(item, index) in options.themeStyle" :key="index">
              <template #title>{{ item.label }}</template>
              <div class="setting-drawer-index-item" @click="setTheme(item.value)">
                <s-icon :type="item.icon" size="48px"></s-icon>
                <div class="setting-drawer-index-selectIcon" v-if="theme === item.value">
                  <s-icon type="check-outlined" />
                </div>
              </div>
            </a-tooltip>
          </div>
        </div>
        <div :style="{ marginBottom: '24px' }">
          <h3 class="setting-drawer-index-title">主题色</h3>

          <div style="height: 20px">
            <a-tooltip
              class="setting-drawer-theme-color-colorBlock"
              v-for="(item, index) in options.themeColors"
              :key="index"
            >
              <template #title>
                {{ item.key }}
              </template>
              <a-tag :color="item.color" @click="changeColor(item.color)">
                <s-icon type="check-outlined" v-if="item.color === primaryColor"></s-icon>
              </a-tag>
            </a-tooltip>
          </div>
        </div>
        <a-divider />

        <div :style="{ marginBottom: '24px' }">
          <h3 class="setting-drawer-index-title">导航模式</h3>
          <div class="setting-drawer-block-checbox">
            <a-tooltip v-for="item in options.layouts" :key="item.value">
              <template #title>{{ item.label }}</template>
              <div class="setting-drawer-index-item" @click="changeSetting('layout', item.value)">
                <s-icon :type="item.icon" size="48px"></s-icon>
                <div class="setting-drawer-index-selectIcon" v-if="layout === item.value">
                  <s-icon type="check-outlined" />
                </div>
              </div>
            </a-tooltip>
          </div>
        </div>

        <a-divider />

        <div class="mt-[24px]">
          <h3 class="setting-drawer-index-title">其他设置</h3>
          <div class="mt-[20px]">
            <a-list :split="false">
              <set-list-item title="路由动画">
                <a-switch
                  size="small"
                  :checked="openAnimation"
                  @change="changeSetting('openAnimation', $event)"
                />
              </set-list-item>

              <set-list-item title="动画类型">
                <a-select
                  size="small"
                  :disabled="!openAnimation"
                  style="width: 105px"
                  :options="options.animation"
                  :value="animation"
                  @change="changeSetting('animation', $event)"
                >
                </a-select>
              </set-list-item>

              <set-list-item title="分割菜单">
                <a-switch
                  size="small"
                  :disabled="layout != 'mix'"
                  :checked="splitMenu"
                  @change="changeSetting('splitMenu', $event)"
                />
              </set-list-item>

              <set-list-item title="面包屑">
                <a-switch
                  size="small"
                  :checked="showBreadCrumb"
                  @change="changeSetting('showBreadCrumb', $event)"
                />
              </set-list-item>

              <set-list-item title="标签页">
                <a-switch
                  size="small"
                  :checked="showMultiTabs"
                  @change="changeSetting('showMultiTabs', $event)"
                />
              </set-list-item>

              <set-list-item title="固定标签页">
                <a-switch
                  size="small"
                  :checked="fixedMultiTabs"
                  @change="changeSetting('fixedMultiTabs', $event)"
                />
              </set-list-item>

              <set-list-item title="标签页类型">
                <a-select
                  size="small"
                  style="width: 105px"
                  :options="options.tabsType"
                  :value="tabsType"
                  @change="changeSetting('tabsType', $event)"
                >
                </a-select>
              </set-list-item>

              <set-list-item title="页面加载条">
                <a-switch
                  size="small"
                  :checked="openNProgress"
                  @change="changeSetting('openNProgress', $event)"
                />
              </set-list-item>

              <set-list-item title="圆角">
                <a-input-number
                  :formatter="value => `${value}px`"
                  :parser="value => value.replace('px', '')"
                  style="width: 105px"
                  :value="borderRadius"
                  @change="changeSetting('borderRadius', $event)"
                  :min="1"
                  :max="16"
                />
              </set-list-item>

              <set-list-item title="主题设置位置">
                <a-segmented
                  style="width: 105px"
                  v-model:value="setPosition"
                  @change="changeSetting('setPosition', $event)"
                  :options="options.setPosiList"
                />
              </set-list-item>
            </a-list>
          </div>
        </div>
      </div>
      <template #handle>
        <div v-if="setPosition == 'fixed'" class="setting-drawer-index-handle" @click="toggle">
          <s-icon :type="visible ? 'close-outlined' : 'setting-outlined'" style="color: #fff" />
        </div>
      </template>
    </a-drawer>
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from "pinia";
import { useAppStore } from "@/store/modules/app";
import * as options from "./options";
import SetListItem from "./SetListItem.vue";
const store = useAppStore();
const {
  layout,
  theme,
  primaryColor,
  animation,
  showBreadCrumb,
  fixedMultiTabs,
  showMultiTabs,
  splitMenu,
  tabsType,
  openAnimation,
  openNProgress,
  borderRadius,
  setPosition
} = storeToRefs(store);

const visible = ref<boolean>(false);

const rootClassName = computed(() => {
  const className = unref(visible) ? "setting-drawer-show" : "";
  return `setting-drawer ${className}`;
});

const onClose = () => {
  visible.value = false;
};

const toggle = () => {
  visible.value = !visible.value;
};

const { changeSetting, setTheme } = store;

const changeColor = (color: string) => {
  changeSetting("primaryColor", color);
};

defineExpose({
  toggle
});
</script>
<style lang="less" scoped>
:global(.setting-drawer .ant-drawer-content-wrapper) {
  display: block !important;
  transform: translate(100%);
  box-shadow: none;
}

:global(.setting-drawer-show .ant-drawer-content-wrapper) {
  transform: translate(0);
}

.setting-drawer-index-content {
  .setting-drawer-block-checbox {
    display: flex;

    .setting-drawer-index-item {
      margin-right: 16px;
      position: relative;
      border-radius: 4px;
      cursor: pointer;

      img {
        width: 48px;
      }

      .setting-drawer-index-selectIcon {
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        padding-top: 15px;
        padding-left: 24px;
        height: 100%;
        color: #1890ff;
        font-size: 14px;
        font-weight: 700;
      }
    }
  }

  .setting-drawer-index-title {
    margin-bottom: 16px;
  }

  :deep(.ant-list .ant-list-item) {
    padding: 8px;
  }

  .setting-drawer-theme-color-colorBlock {
    width: 20px;
    height: 20px;
    border-radius: 2px;
    float: left;
    cursor: pointer;
    margin-right: 8px;
    padding-left: 0px;
    padding-right: 0px;
    text-align: center;
    color: #fff;
    font-weight: 700;

    i {
      font-size: 14px;
    }
  }
}

.setting-drawer-index-handle {
  width: 35px;
  height: 35px;
  background: rgba(0, 0, 0, 0.38);
  position: absolute;
  top: 240px;
  right: 310px;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  pointer-events: auto;
  z-index: 1001;
  text-align: center;
  font-size: 16px;
  border-radius: 4px 0 0 4px;

  i {
    color: rgb(255, 255, 255);
    font-size: 20px;
  }
}

[data-theme="dark"] {
  .setting-drawer-index-handle {
    background: #817e7e80;
  }
  .custom-close-btn{
    .anticon {
      color: #fff;
    }
    &:hover {
      opacity: 0.8;
      background: #5b5b5b;
    }
  }
}

.custom-close-btn {
  position: absolute;
  width: 25px;
  height: 25px;
  right: 14px;
  top: 8px;
  font-size: 14px;
  cursor: pointer;
  z-index: 10;
  padding: 14px;
  border-radius: 50%;
  & .anticon {
   color:rgba(0, 0, 0, 0.45);
  }
  &:hover {
    opacity: 0.8;
    background: #eee;
  }
}
</style>

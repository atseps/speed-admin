<template>
  <page-view>
    <a-card :bordered="false" :bodyStyle="{ padding: '16px 0', height: '100%' }">
      <div class="account-settings-info-main" :class="{ mobile: isMobile }">
        <div class="account-settings-info-left">
          <a-menu
            :mode="isMobile ? 'horizontal' : 'inline'"
            :style="{ border: '0', width: isMobile ? '560px' : 'auto' }"
            type="inner"
            v-model:selectedKeys="selectedKeys"
          >
            <a-menu-item @click="onClick(item)" v-for="item in tabs" :key="item.key">
              <span>{{ item.name }}</span>
            </a-menu-item>
          </a-menu>
        </div>
        <div class="account-settings-info-right">
          <div class="account-settings-info-title">{{ title }}</div>
          <Info v-if="selectedKeys[0] === 'info'" />
          <Basic v-if="selectedKeys[0] === 'basic'" />
          <edit-pwd v-else-if="selectedKeys[0] === 'editPwd'" />
        </div>
      </div>
    </a-card>
  </page-view>
</template>

<script setup lang="ts">
import { useSetting } from "@/layout/hooks";
import Info from "./Info.vue";
import Basic from "./Basic.vue";
import EditPwd from "./EditPwd.vue";
type TabsType = {
  key: string;
  name: string;
};
const { isMobile } = useSetting();
const selectedKeys = ref(["basic"]);
const title = ref("基础设置");
const tabs: TabsType[] = [
  {
    key: "basic",
    name: "基础设置"
  },
  {
    key: "info",
    name: "个人信息"
  },
  {
    key: "editPwd",
    name: "修改密码"
  }
];
function onClick(item: TabsType) {
  title.value = item.name;
}
</script>

<style lang="less" scoped>
.account-settings-info-main {
  width: 100%;
  display: flex;
  height: 100%;
  overflow: auto;

  &.mobile {
    display: block;

    .account-settings-info-left {
      border-right: unset;
      //border-bottom: 1px solid #e8e8e8;
      width: 100%;
      height: 50px;
      overflow-x: auto;
      overflow-y: scroll;
    }

    .account-settings-info-right {
      padding: 20px 40px;
    }
  }

  .account-settings-info-left {
    border-right: 1px solid var(--ant-color-border-secondary);
    width: 224px;
  }

  .account-settings-info-right {
    flex: 1 1;
    padding: 8px 40px;

    .account-settings-info-title {
      font-size: 20px;
      font-weight: 500;
      line-height: 28px;
      margin-bottom: 12px;
    }

    .account-settings-info-view {
      padding-top: 12px;
    }
  }
}
</style>

<template>
  <div class="user-menu flex">
    <a-space warp :size="4">
      <s-button
        @click="openSetting"
        shape="circle"
        :iconSize="13"
        v-if="appStore.setPosition == 'header'"
        class="user-menu-icon"
        icon="setting-outlined"
      />
      <s-button
        @click="onChangeTheme($event)"
        shape="circle"
        class="user-menu-icon"
        :icon="appStore.theme == 'realDark' ? 'svg:sun' : 'svg:moon'"
      />
    </a-space>
    <a-dropdown class="user-dropdown">
      <span class="action ant-dropdown-link user-dropdown-menu">
        <a-avatar class="avatar" size="small" :src="userInfo.avatar" />
        <span class="account-name line-feed-1">{{ userInfo.realname }}</span>
      </span>
      <template #overlay>
        <a-menu>
          <a-menu-item @click="toAccount">
            <s-icon type="user-outlined" class="icon" />
            <span>个人中心</span>
          </a-menu-item>
          <a-menu-item @click="logout">
            <a href="javascript:;">
              <s-icon type="logout-outlined" class="icon" />
              <span>退出登录 <a-spin :spinning="spinning" :indicator="indicator"></a-spin></span>
            </a>
          </a-menu-item>
        </a-menu>
      </template>
    </a-dropdown>
  </div>
</template>
<script setup lang="ts">
import { useUserStore } from "@/store/modules/user";
import { useAppStore, AppState } from "@/store/modules/app";
import { storeToRefs } from "pinia";
import { themeAnimation } from "@/utils";
import { LoadingOutlined } from '@ant-design/icons-vue';
const indicator = h(LoadingOutlined, {
  style: {
   fontSize: '12px',
   color: '#aaa',
  },
  spin: true,
});
const store = useUserStore();
const { userInfo } = storeToRefs(store);
const appStore = useAppStore();
const router = useRouter();

// 注入打开设置的方法
const openSetting = inject("openSetting") as () => void;

const spinning = ref(false);
const logout = () => {
  spinning.value = true;
  store.logout().then(()=>{
    spinning.value = false;
  });
};

const toAccount = () => {
  router.push({ path: "/account" });
};

const prevTheme = ref("light");

const onChangeTheme = (event: MouseEvent) => {
  const { theme, setTheme } = appStore;
  if (theme != "realDark") {
    prevTheme.value = theme;
  }
  const value = theme != "realDark" ? "realDark" : prevTheme.value;
  const isDark = value == "realDark" ? true : false;
  themeAnimation(event, isDark, () => {
    setTheme(value as AppState["theme"]);
  });
};
</script>

<style lang="less" scoped>
.action {
  padding: 0 8px;
  &:hover {
    background: rgba(0, 0, 0, 0.025);
  }
}

.icon {
  min-width: 12px;
  margin-right: 8px;
}

.user-dropdown-menu {
  display: flex;
  height: 48px;
  align-items: center;
  vertical-align: top;
  cursor: pointer;
  margin-left: 6px;
  span {
    user-select: none;
  }
}
.user-menu-icon {
  border: none;
  box-shadow: none;
  &:hover {
    background: #eee;
    color: initial;
  }
}
.account-name {
  font-size: 16px;
  transform: none;
  color: #fff;
  text-transform: none;
  vertical-align: -0.125em;
  margin-left: 6px;
}

.header-dark {
  .user-menu-icon {
    background: #001529;
    color: #fff;
  }
}

[data-theme="dark"] {
  .user-menu-icon {
    background: transparent;
    color: #fff;
    &:hover {
      background: #383838;
      color: #fff;
    }
  }
}
</style>

import { defineStore } from "pinia";
import config from "@/config";
import { getCssVar } from "@/utils"
import storage from "@/utils/storage";
import { store } from "@/store";
import type { MenuTheme } from "ant-design-vue/es/menu";
import { theme as antdTheme } from "ant-design-vue";
export type AppState = {
  layout: "side" | "top" | "mix" | "left"; //布局模式
  device: "desktop" | "mobile" | "tablet"; //设备类型
  sidebarOpened: boolean; //侧边栏展开状态
  theme: MenuTheme | 'realDark'; //主题
  primaryColor: string; //主题色
  animation: string; //路由动画
  showBreadCrumb: boolean; //是否显示面包屑导航栏，在top和mix模式下有效
  showMultiTabs: boolean; //是否显示标签页
  splitMenu: boolean; //是否自动分割菜单，在mix模式下生效
  fixedMultiTabs: boolean; //固定标签页
  tabsType: "smooth-tab" | "smart-tab"; //多标签类型
  openAnimation: boolean; //是否启用路由动画
  openNProgress: boolean; //是否开启页面进度条
  showFooter: boolean; //是否显示底部
  setPosition: "header" | "fixed"; // 主题设置位置
  borderRadius: number; //圆角
};

type Key = keyof AppState;

const getState = (key: Key) => {
  return storage.get(key, (config as Recordable)[key]);
};

export const useAppStore = defineStore("app", {
  state: (): AppState => ({
    device: "desktop",
    sidebarOpened: true,
    layout: getState("layout"),
    animation: getState("animation"),
    primaryColor: getState("primaryColor"),
    theme: getState("theme"),
    showBreadCrumb: getState("showBreadCrumb"),
    showMultiTabs: getState("showMultiTabs"),
    splitMenu: getState("splitMenu"),
    fixedMultiTabs: getState("fixedMultiTabs"),
    tabsType: getState("tabsType"),
    openAnimation: getState("openAnimation"),
    openNProgress: getState("openNProgress"),
    showFooter: getState("showFooter"),
    setPosition: getState("setPosition"),
    borderRadius: getState("borderRadius")
  }),
  getters: {
    //是否移动端
    isMobile(state) {
      return state.device === "mobile";
    },
    themeSetting(state) {
      const isDark = state.theme == "realDark";
      return {
        algorithm: isDark ? antdTheme.darkAlgorithm : antdTheme.defaultAlgorithm,
        token: {
          colorPrimary: state.primaryColor,
          colorBgLayout: isDark ? "#000000" : getCssVar("--spe-layout-bg-color"),
          borderRadius: state.borderRadius
        },
        components: isDark
          ? {
            Menu: {
              colorItemBg: "rgb(20 20 20)",
              colorSubItemBg: "rgb(36, 37, 37)",
              menuSubMenuBg: "rgb(36, 37, 37)"
            }
          }
          : {}
      };
    }
  },
  actions: {
    //更改设置
    changeSetting(key: Key, value: any) {
      if (this.$state.hasOwnProperty(key)) {
        this.$patch({ [key]: value });
        storage.set(<string>key, value);
      }
    },
    setTheme(theme: AppState["theme"]) {
      this.theme = theme;
      setRealDarkTheme(theme);
      storage.set("theme", theme);
    }
  }
});

// 设置暗黑主题
export const setRealDarkTheme = (theme: AppState["theme"]) => {
  if (theme === "realDark") {
    document.documentElement.setAttribute("data-theme", "dark");
  } else {
    document.documentElement.setAttribute("data-theme", "light");
  }
};

//监听操作系统主题变更事件
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", (e)=>{
  const { setTheme } = useAppStore(store);
  if (e.matches) {
    setTheme("realDark");
  } else {
    setTheme("light");
  }
})

import type { AppState } from "@/store/modules/app";
export const themeStyle = [
  {
    label: "亮色主题风格",
    icon:"svg:theme-light",
    value: "light" as AppState['theme']
  },
  {
    label: "暗色主题风格",
    icon: "svg:theme-dark",
    value: "dark" as AppState['theme']
  },
  {
    label: "暗黑模式",
    icon: "svg:theme-real-dark",
    value: "realDark" as AppState['theme']
  }
];

/** 主题色 */
export const themeColors = [
  {
    key: "极客蓝（默认）",
    color: "#4073fa"
  },
  {
    key: "拂晓蓝",
    color: "#1677ff"
  },
  {
    key: "薄暮",
    color: "#f5222d"
  },
  {
    key: "火山",
    color: "#fa541c"
  },
  {
    key: "日暮",
    color: "#ff9900"
  },
  {
    key: "苍岭绿",
    color: "#27b59c"
  },
  {
    key: "海湾蓝",
    color: "#536DFE"
  },
  {
    key: "黛紫",
    color: "#955ce6"
  }
];

/** 导航模式（布局方式） */
export const layouts = [
  {
    label: "侧边菜单布局",
    icon: "svg:layout-side",
    value: "side"
  },
  {
    label: "顶部菜单布局",
    icon: "svg:layout-top",
    value: "top"
  },
  {
    label: "混合布局",
    icon:"svg:layout-mix",
    value: "mix"
  },
  {
    label: "左侧混合布局",
    icon: "svg:layout-left",
    value: "left"
  }
];

/** 路由动画 */
export const animation = [
  {
    value: "fade-slide",
    label: "滑动消退"
  },
  {
    value: "fade-bottom",
    label: "底部消退"
  },
  {
    value: "fade-top",
    label: "顶部消退"
  },
  {
    value: "fade-scale",
    label: "缩小渐变"
  },
  {
    value: "zoom-fade",
    label: "放大渐变"
  }
];

//多标签类型
export const tabsType = [
  {
    value: "smooth-tab",
    label: "圆滑"
  },
  {
    value: "smart-tab",
    label: "灵动"
  }
];

//主题设置位置
export const setPosiList = [
  {
    value: "header",
    label: "顶栏"
  },
  {
    value: "fixed",
    label: "固定"
  }
];

import useSetting from "./useSetting";
import { layoutConfig } from "@/config";

const { sidebarWidth, collapsedWidth, leftNavWdith, headerHeight } = layoutConfig;

export default function useHeaderSetting() {
  const { sidebarOpened, layout, isMobile, showBreadCrumb } = useSetting();

  //头部的宽度
  const width = computed(() => {
    let reduceWidth = unref(sidebarOpened) ? sidebarWidth : collapsedWidth;
    if (unref(layout) == "left") reduceWidth = leftNavWdith;
    if (["top", "mix"].includes(unref(layout)) || unref(isMobile)) reduceWidth = 0;
    return `calc(100% - ${reduceWidth}px)`;
  });

  //是否显示头部菜单
  const showTopMenu = computed(() => {
    return (unref(layout) == "top" || unref(layout) == "mix") && !unref(isMobile);
  });

  //头部的高度
  const height = computed(() => {
    return `${headerHeight}px`;
  });

  //是否侧边栏或者移动端
  const sideOrMobile = computed(() => {
    return unref(layout) == "side" || unref(isMobile);
  });

  //是否显示面包屑导航栏
  const showBreadcrumb = computed(() => {
    if (unref(isMobile)) return false;
    if (["side", "left"].includes(unref(layout)) && unref(showBreadCrumb)) {
      return true;
    }
    return false;
  });

  return {
    width,
    height,
    showTopMenu,
    sideOrMobile,
    showBreadcrumb
  };
}

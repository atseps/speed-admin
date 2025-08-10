import { storeToRefs } from "pinia";
import { useAppStore } from "@/store/modules/app";
import { usePermissionStore } from "@/store/modules/permission";
import { layoutConfig } from "@/config";
import { httpReg } from "@/utils";

const { sidebarWidth, collapsedWidth, leftNavWdith, leftSubWidth } = layoutConfig;
export default function useSetting() {
  const appStore = useAppStore();
  const permissionStore = usePermissionStore();
  const { changeSetting } = appStore;
  const { menus } = storeToRefs(permissionStore);
  const route = useRoute();
  const router = useRouter();

  const {
    layout,
    theme,
    isMobile,
    sidebarOpened,
    animation,
    showMultiTabs,
    fixedMultiTabs,
    showBreadCrumb,
    splitMenu,
    tabsType,
    openAnimation,
    showFooter
  } = storeToRefs(appStore);

  //是否显示侧边栏
  const showSidebar = computed(() => {
    //手机端显示侧边栏
    if (unref(isMobile)) return true;
    //顶部菜单布局不显示侧边栏
    if (unref(layout) == "top") return false;
    //混合布局 且没有侧边栏数据
    if (unref(layout) == "mix" && !unref(sideLength)) {
      return false;
    }
    return true;
  });

  //是否left布局且不是移动端
  const isLeftNotMobile = computed(() => {
    return unref(layout) == "left" && !unref(isMobile);
  });

  //是否是left和mix布局
  const isMinxLayout = computed(() => {
    return ["mix", "left"].includes(unref(layout));
  });

  //侧边栏菜单的长度
  const sideLength = computed(() => sideMenus.value.length);

  //侧边栏的宽度
  const sideWidth = computed(() => {
    const sideWidth = unref(sidebarOpened) ? sidebarWidth : collapsedWidth;
    if (unref(isLeftNotMobile)) return leftSubWidth;
    return sideWidth;
  });

  //占位盒子的宽度
  const stuffWidth = computed(() => {
    if (unref(layout) == "left") {
      if (!unref(sideLength)) return leftNavWdith;
      return unref(sideWidth) + leftNavWdith;
    }
    return unref(sideWidth);
  });

  //侧边栏导航主题
  const navTheme = computed(() => {
    if (unref(isMinxLayout) && unref(theme) != "realDark") {
      return "light";
    }
    return unref(theme) === "light" ? "light" : "dark";
  });

  //头部主题
  const headTheme = computed(() => {
    if (["side", "left"].includes(unref(layout)) && unref(theme) != "realDark") {
      return "light";
    }
    return unref(theme) === "light" ? "light" : "dark";
  });

  //侧边栏布局的left
  const siderLeft = computed(() => {
    const left = unref(isLeftNotMobile) ? `${leftNavWdith}px` : 0;
    return { left };
  });

  //需要减去的宽度
  const reduceWidth = computed(() => {
    let reduceWidth = unref(sidebarOpened) ? sidebarWidth : collapsedWidth;
    if (unref(layout) == "left") reduceWidth = unref(stuffWidth);
    if (unref(layout) == "top" || unref(isMobile)) reduceWidth = 0;
    if (!unref(sideLength)) {
      reduceWidth = unref(layout) == "left" ? leftNavWdith : 0;
    }
    return reduceWidth;
  });

  //多标签的宽度
  const tabsWidth = computed(() => {
    const width = unref(reduceWidth);
    return unref(fixedMultiTabs) ? `calc(100% - ${width}px)` : "auto";
  });

  //侧边栏菜单
  const sideMenus = computed(() => {
    const isMixOrLeftLayout = unref(layout) == "mix" && unref(splitMenu) || unref(layout) === "left";
    if (isMixOrLeftLayout && !unref(isMobile)) {
      const menu = unref(menus).find(v => v.path === route.matched[0].path && !v.hideChildrenInMenu)?.children || [];
      changeSetting("sidebarOpened", true);
      return menu;
    }
    return unref(menus);
  });

  //路由跳转
  const routerTo = (data: Recordable) => {
    const path = data.key || data.path;
    if (httpReg(path)) {
      window.open(path.slice(1));
    } else {
      router.push({ path: path });
    }
  };

  const showSideTrigger = computed(() => {
    return unref(layout) == "mix" && !unref(splitMenu);
  });

  return {
    layout,
    theme,
    isMobile,
    showSidebar,
    sidebarOpened,
    menus,
    changeSetting,
    sideWidth,
    sideMenus,
    stuffWidth,
    isMinxLayout,
    navTheme,
    headTheme,
    siderLeft,
    isLeftNotMobile,
    animation,
    showBreadCrumb,
    showMultiTabs,
    fixedMultiTabs,
    tabsWidth,
    routerTo,
    tabsType,
    splitMenu,
    openAnimation,
    reduceWidth,
    showFooter,
    showSideTrigger
  };
}

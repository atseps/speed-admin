import { useUserStore } from "@/store/modules/user";
import { usePermissionStore } from "@/store/modules/permission";
import { useAppStore } from "@/store/modules/app";
import NProgress from "nprogress";
import "nprogress/nprogress.css";
import { getAccessToken } from "@/utils/auth";
import { httpReg, setTitle } from "@/utils";
import { generatorDynamicRouter } from "@/router/router";
import { RouteRecord } from "@/router/types";
import type { Router } from "vue-router";
import { notification } from "ant-design-vue";
export function setupRouterGuard(router: Router) {
  NProgress.configure({ showSpinner: false });

  const whiteList = ["/login"];

  router.beforeEach(async (to) => {
    const userStore = useUserStore();
    const appStore = useAppStore();
    const permissionStore = usePermissionStore();
    // 开启页面加载条
    appStore.openNProgress && NProgress.start();

    // 设置页面标题
    document.title = setTitle(to.meta.title as string);

    // 确定用户是否已登录
    const hasToken = getAccessToken();

    if (hasToken) {
      if (to.path === "/login") {
        // 如果已登录，则重定向到首页
        NProgress.done();
        return { path: "/" };
      } else {
        // 是否已获取用户角色
        const hasRoles = userStore.roles && userStore.roles.length > 0;
        if (hasRoles) {
          return true;
        } else {
          try {
            // 重新获取用户信息
            await userStore.getUserInfo();
            // 获取路由
            const accessRoutes: RouteRecord[] = await generatorDynamicRouter();
            // 设置菜单
            permissionStore.setMenus(accessRoutes);
            // 添加路由
            accessRoutes.forEach((item: any) => {
              // 外链不加入路由
              if (httpReg(item.path)) {
                return;
              }
              router.addRoute(item);
            });
            // 路由跳转
            return { path: to.path, replace: true, query: to.query };
          } catch (error: any) {
            // 获取用户信息失败
            if (error?.status === 401) {
              return { path: "/login" };
            }
            notification.error({
              message: "错误",
              description: "请求用户信息失败，请重试"
            });
            await userStore.logout();
            return { path: "/login", query: { redirect: to.fullPath } };
          }
        }
      }
    } else {
      // 不存在令牌
      if (whiteList.includes(to.path)) {
        return true;
      } else {
        NProgress.done();
        return { path: "/login", query: { redirect: to.fullPath } };
      }
    }
  });

  router.afterEach(() => {
    NProgress.done();
  });
}

import { createRouter, createWebHistory } from "vue-router";
import { treeToList } from "@/utils/helper/treeHelper";
import type { RouteRecordRaw } from "vue-router";
/**
 * 基础路由
 */
export const constantRoutes: RouteRecordRaw[] = [
  {
    name: "login",
    path: "/login",
    component: () => import("@/views/user/login.vue")
  },
  {
    name: "account",
    path: "/account",
    component: () => import("@/layout/Layout.vue"),
    redirect: "/account/center",
    children: [
      {
        name: "center",
        path: "center",
        component: () => import("@/views/user/center/index.vue"),
        meta: { title: "个人中心" }
      }
    ]
  },
  {
    name: "404",
    path: "/:pathMatch(.*)*",
    component: () => import("@/views/exception/404.vue")
  }
];

//创建路由
const router = createRouter({
  history: createWebHistory(import.meta.env.VITE_APP_BASE_URL),
  routes: constantRoutes,
  scrollBehavior() {
    // 始终滚动到顶部
    return { top: 0 };
  }
});

// 重置路由
export function resetRouter() {
  const baseRoute = treeToList(constantRoutes);
  router.getRoutes().forEach(route => {
    const { name } = route;
    if (name && !baseRoute.some((item: RouteRecordRaw) => item?.name === name)) {
      router.hasRoute(name) && router.removeRoute(name);
    }
  });
}

export default router;

import { defineStore } from "pinia";
import { RouteRecord as RouteMenu } from "@/router/types"
interface PermissionState {
  menus: Array<RouteMenu>;
}
export const usePermissionStore = defineStore("permission", {
  state: (): PermissionState => ({
    menus: [] // 菜单数据
  }),
  actions: {
    setMenus(menus: Array<RouteMenu>) {
      this.menus = menus;
    }
  }
});


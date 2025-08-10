import { useUserStore } from "@/store/modules/user";
import { isString } from "@/utils/is";
import type { Directive, App } from "vue";
const roleDirective: Directive = {
  mounted(el, binding) {
    const userStore = useUserStore();
    let { value, arg } = binding;
    const roles = userStore.roles;
    //如果是字符串转成数组
    if (value && isString(value)) value = [value];

    if (value && value instanceof Array && value.length > 0) {
      const permissionRoles = value;
      //判断指令角色是否存在用户角色中
      const hasPermission = roles.some(role => {
        return permissionRoles.includes(role);
      });

      if (arg != undefined && arg === "not") {
        if (hasPermission) el.parentNode && el.parentNode.removeChild(el);
      } else {
        if (!hasPermission) el.parentNode && el.parentNode.removeChild(el);
      }
    } else {
      throw new Error(`need roles! Like v-role="['admin','editor']"`);
    }
  }
};

export function setupRoleDirective(app: App) {
  app.directive("role", roleDirective);
}

export default roleDirective;

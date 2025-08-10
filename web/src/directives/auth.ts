import { useUserStore } from "@/store/modules/user";
import { isString } from "@/utils/is";
import type { Directive, App } from "vue";
const authDirective: Directive = {
  mounted(el, binding) {
    const userStore = useUserStore();
    const { value } = binding;
    const rules = userStore.rules;
    if (value && isString(value)) {
      const hasPermission = rules.includes(value);
      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el);
      }
    } else {
      throw new Error(`need roles! Like v-auth="'posts:index:update'"`);
    }
  }
};

export function setupAuthDirective(app: App) {
  app.directive("auth", authDirective);
}

export default authDirective;

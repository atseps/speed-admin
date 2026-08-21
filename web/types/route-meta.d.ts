// 扩展 vue-router 的 RouteMeta 业务侧字段
import "vue-router";

declare module "vue-router" {
  interface RouteMeta {
    title?: string;
    icon?: string;
    active_key?: string;
    hidden?: boolean;
    type?: number;
    url?: string;
    namePath?: Array<string>;
    breadcrumb?: boolean;
  }
}

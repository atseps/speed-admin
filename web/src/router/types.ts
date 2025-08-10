import { type RouteComponent } from "vue-router";

export enum MenuEnum {
  CATALOGUE = 0, //目录
  MENU = 1, //菜单
  BUTTON = 2 //权限
}

export interface RouterMenu {
  path: string;
  component: string;
  title: string;
  icon: string;
  hidden?: boolean;
  type?: MenuEnum;
  hide_children?: number | boolean;
  active_key?: string;
  link_url?: string;
  redirect?: string;
  children?: RouterMenu[];
  breadcrumb?: boolean;
}

interface RouteRecordMeta {
  title: string;
  icon?: string;
  active_key?: string;
  type?: MenuEnum;
  url?: string;
  hidden?: boolean;
  namePath?: Array<string>;
  breadcrumb?: boolean;
}

export interface RouteRecord {
  path: string;
  hidden: boolean;
  meta: RouteRecordMeta;
  component?: RouteComponent | null | undefined;
  children?: RouteRecord[];
  redirect?: string;
  hideChildrenInMenu?: boolean;
  name?: string | Symbol;
}

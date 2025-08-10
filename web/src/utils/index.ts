import { cloneDeep, keys, pick } from "lodash-es";
import config from "@/config";
import { isObject } from "./is";
export function timeFix() {
  const time = new Date();
  const hour = time.getHours();
  return hour < 9
    ? "早上好"
    : hour <= 11
    ? "上午好"
    : hour <= 13
    ? "中午好"
    : hour < 20
    ? "下午好"
    : "晚上好";
}

/**
 * 设置页面标题
 *
 * @param pageTitle string  页面标题
 * @returns {title}
 */
export function setTitle(pageTitle: string): string {
  const title = config.appName || "SpeedAdmin";
  if (pageTitle) {
    return `${pageTitle} - ${title}`;
  }
  return `${title}`;
}

//设置css变量
export const setCssVar = (prop: string, val: string | number, dom = document.documentElement) => {
  dom.style.setProperty(prop, String(val));
};

/**
 * 读取指定元素的 CSS 变量值，支持默认值
 * @param {string} variableName - CSS 变量名（例如：--primary-color）
 * @param {string} [defaultValue=''] - 默认值，当变量不存在时返回
 * @param {Element} [element=document.documentElement] - 目标元素，默认为根元素（<html>）
 * @returns {string} - CSS 变量的值或默认值
 */
export function getCssVar(
  variableName: string,
  defaultValue = "",
  element: Element = document.documentElement
): string {
  if (!variableName || typeof variableName !== "string") {
    throw new Error("Invalid variable name. Expected a string.");
  }

  const value = getComputedStyle(element).getPropertyValue(variableName);
  return value ? value.trim() : defaultValue;
}

/**
 * 遍历树状数据,每次遍历会触发回调
 *
 * @param data Tree  树状数据
 * @param callback   回调函数,每次遍历都会触发,(当前值、父节点的值、深度)
 */

export function treeEach(data: Array<any>, callback: Function) {
  const inFn = (list: Array<any>, parentMenu: Array<any> | null, depth: number) => {
    for (const item of list) {
      callback(item, parentMenu, depth);
      if (item.children && item.children.length > 0) {
        inFn(item.children, item, depth + 1);
      }
    }
  };
  inFn(data, null, 1);
}

/**
 * 数组去重
 * @param {Array} arr
 * @returns {Array}
 */
export function uniqueArr<T = any>(arr: Array<T>): Array<T> {
  return Array.from(new Set(arr));
}

/**
 * 数组两个值相等触发回调
 * @param {Array} arr
 * @param {Array} arr
 * @returns {Array}
 */
export function interseArr(array1: Array<any>, array2: Array<any>, callback: Function) {
  const length1 = array1.length;
  const length2 = array2.length;
  for (let i = 0; i < length1; i++) {
    for (let j = 0; j < length2; j++) {
      if (array1[i] == array2[j]) callback(array1[i]);
    }
  }
}

export function download(
  response: { data: Blob; headers: { [key: string]: string } },
  mimeType = "csv"
) {
  const mimeMap: Recordable = {
    xlsx: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    zip: "application/zip",
    csv: "application/vnd.ms-excel;charset=utf-8"
  };
  const aLink = document.createElement("a");
  const blob = new Blob([response.data], { type: mimeMap[mimeType] });
  const patt = new RegExp("filename=([^;]+\\.[^\\.;]+);*");
  const contentDisposition = decodeURI(response.headers["content-disposition"]);
  const result = patt.exec(contentDisposition) as RegExpExecArray;
  let fileName = result[1] ?? "";
  fileName = fileName.replace(/"/g, "");
  const href = URL.createObjectURL(blob);
  aLink.href = href;
  aLink.setAttribute("download", fileName); // 设置下载文件名称
  document.body.appendChild(aLink);
  aLink.click();
  document.body.removeChild(aLink); // 下载完成移除元素
  window.URL.revokeObjectURL(href); // 释放掉blob对象
}

export function toOptions(data: Array<Recordable>, isToString = false) {
  if (Array.isArray(data)) {
    data.forEach(item => {
      item.label = item.name;
      item.text = item.name;
      item.value = isToString ? `${item.id}` : item.id;
    });
  }
  return data;
}

/**
 * 获取扩展名
 */
export function getExtname(url: string): string {
  const temp = url.split("/");
  const filename = temp[temp.length - 1];
  const filenameWithoutSuffix = filename.split(/#|\?/)[0];
  return (/\.[^./\\]*$/.exec(filenameWithoutSuffix) || [""])[0];
}

/**
 * 判断是否是网址
 */
export function httpReg(url: string): boolean {
  const reg = /^(\/http|\/https|http|https|ftp|):\/\/([\w.]+\/?)\S*/;
  return reg.test(url);
}

// dynamic use hook props
export function getDynamicProps<T extends Record<string, unknown>, U>(props: T): Partial<U> {
  const ret: Recordable = {};

  Object.keys(props).forEach(key => {
    ret[key] = unref((props as Recordable)[key]);
  });

  return ret as Partial<U>;
}

// 深度合并
export function deepMerge<T = any>(src: any = {}, target: any = {}): T {
  let key: string;
  const res: any = cloneDeep(src);
  for (key in target) {
    res[key] = isObject(res[key]) ? deepMerge(res[key], target[key]) : (res[key] = target[key]);
  }
  return res;
}

/**
 * @description: Get environment variables
 * @returns:
 * @example:
 */
export function getEnv(): string {
  return import.meta.env.MODE;
}

/**
 * @description: Is it a development mode
 * @returns:
 * @example:
 */
export function isDevMode(): boolean {
  return import.meta.env.DEV;
}

/**
 * @description: Is it a production mode
 * @returns:
 * @example:
 */
export function isProdMode(): boolean {
  return import.meta.env.PROD;
}

// 获取assets静态资源
export const getAssetsFile = (url: string) => {
  return new URL(`../assets/images/${url}`, import.meta.url).href;
};

//保持对象的引用，并清空对象的值
export const clearObject = (obj: Recordable) => {
  Object.keys(obj).forEach(key => {
    obj[key] = undefined;
  });
};

/**
 * 表单赋值
 * @param {Array} data 数据
 * @param {Array} form 表单数据对象
 * @returns {Array}
 */
export function setFormValue(data: Recordable, form: Recordable): Recordable {
  for (const key in data) {
    if (data[key] == null) data[key] = undefined;
  }
  return pick(data, keys(form));
}

/**
 * 生成随机字符串
 * @param {length}  生成字符串的长度
 * @returns {string}
 */
export function generateRandomStr(length = 8) {
  const characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  let result = "";
  for (let i = 0; i < length; i++) {
    const randomIndex = Math.floor(Math.random() * characters.length);
    result += characters[randomIndex];
  }
  return result;
}

/**
 *
 * @param {*} event //点击节点
 * @param {*} isDark  // 当前是否暗黑模式
 * @param {*} callback // 回调 -- 用于切换项目模式
 * @returns
 */
export function themeAnimation(event: MouseEvent, isDark: boolean, callback: Fn) {
  if (!callback) return;
  // @ts-ignore
  if (!document.startViewTransition) return callback();
  const x = event.clientX;
  const y = event.clientY;
  const endRadius = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));
  // @ts-ignore
  const transition = document.startViewTransition(callback);
  transition.ready.then(() => {
    const clipPath = [`circle(0px at ${x}px ${y}px)`, `circle(${endRadius}px at ${x}px ${y}px)`];
    document.documentElement.animate(
      {
        clipPath: isDark ? [...clipPath].reverse() : clipPath
      },
      {
        duration: 400,
        easing: "ease-in",
        pseudoElement: isDark ? "::view-transition-old(root)" : "::view-transition-new(root)"
      }
    );
  });
}

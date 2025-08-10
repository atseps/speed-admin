import { getExtname } from "@/utils";
export function is(val: unknown, type: string) {
  return toString.call(val) === `[object ${type}]`;
}

/** 是否为数字 */
export function isNum(value: number) {
  return /^((-?\d+\.\d+)|(-?\d+)|(-?\.\d+))$/.test(value.toString());
}

/** 是否为整数 */
export function isInt(value: number) {
  return isNum(value) && parseInt(value.toString(), 10) == value;
}

/** 是否为小数 */
export function isDecimal(value: number) {
  return isNum(value) && !isInt(value);
}

/** 是否为身份证 */
export function isIdCard(value: string) {
  return typeof value === "string" && /(^\d{15}$)|(^\d{17}([0-9]|X)$)/i.test(value);
}

/** 是否为手机号 */
export function isMobile(value: string) {
  return (
    typeof value === "string" &&
    /^(0|\+?86|17951)?(13[0-9]|15[0-9]|17[0678]|18[0-9]|14[57])[0-9]{8}$/.test(value)
  );
}

/** 是否为URL地址 */
/** 是否为URL地址 */
export function isURL(url: string, protocols: string[] = ['http:', 'https:']) {
  try {
    const urlObj = new URL(url);
    return protocols.includes(urlObj.protocol);
  } catch (err) {
    return false;
  }
}

/** 是否为邮箱 */
export function isEmail(email: string) {
  const reg =
    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return reg.test(email);
}

/**
 * 是否字符串
 * @param {string} str
 * @returns {Boolean}
 */
export function isString(str: unknown) {
  if (typeof str === "string" || str instanceof String) {
    return true;
  }
  return false;
}

/**
 * 是否数组
 * @param {Array} arg
 * @returns {Boolean}
 */
export function isArray(val: any): val is Array<any> {
  return val && Array.isArray(val);
}

/**
 * 是否对象
 * @param {Array} arg
 * @returns {Boolean}
 */
export function isObject<T = unknown>(obj: T) {
  return Object.prototype.toString.call(obj) === "[object Object]";
}

/**
 * 是否空对象
 * @param {Array} arg
 * @returns {Boolean}
 */
export function isEmptyObject(obj: Recordable) {
  return Object.keys(obj).length === 0;
}

//是否为空，可判断字符串、数组和对象
export function isEmpty<T = unknown>(val: T): val is T {
  if (isArray(val) || isString(val)) {
    return (val as Array<any> | string).length === 0;
  }

  if (val instanceof Map || val instanceof Set) {
    return val.size === 0;
  }

  if (isObject(val)) {
    return Object.keys(val as Object).length === 0;
  }

  return false;
}

//是否函数
export function isFunction(val: unknown): val is Function {
  return typeof val === "function";
}

export function isNumber(val: unknown): val is number {
  return is(val, "Number");
}

export function isBoolean(val: unknown): val is boolean {
  return is(val, "Boolean");
}

export function isUnDef<T = unknown>(val?: T): val is T {
  return !isDef(val);
}

export function isDef<T = unknown>(val?: T): val is T {
  return typeof val !== "undefined";
}

export function isDate(val: unknown): val is Date {
  return is(val, "Date");
}

export function isNull(val: unknown): val is null {
  return val === null;
}

export function isPromise<T = any>(val: any): val is Promise<T> {
  return is(val, "Promise") && isObject(val) && isFunction(val.then) && isFunction(val.catch);
}

/**
 * 是否是IE浏览器
 */
export function isIE() {
  const bw = window.navigator.userAgent;
  const compare = (s: any) => bw.indexOf(s) >= 0;
  const ie11 = (() => "ActiveXObject" in window)();
  return compare("MSIE") || ie11;
}

/**
 * 是否图片地址
 */
export function isImageUrl(url: string) {
  const extension = getExtname(url);
  if (
    /^data:image\//.test(url) ||
    /(webp|svg|png|gif|jpg|jpeg|jfif|bmp|dpg|ico)$/i.test(extension)
  ) {
    return true;
  }
  if (/^data:/.test(url)) {
    // other file types of base64
    return false;
  }
  if (extension) {
    // other file types which have extension
    return false;
  }
  return true;
}

import request from "@/utils/request";

/**
 * 获取数据字典
 * @param type
 * @param str
 */
export function getDict(type: string[] | string, str = 0) {
  const params: Recordable = { type };
  if (str) {
    params.str = str;
  }
  return request.get("dict/get", { params });
}



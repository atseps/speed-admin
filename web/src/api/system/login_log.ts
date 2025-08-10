import request from "@/utils/request";

/**
 * 获取登录日志列表
 * @param params
 */
export function getLoginLogList(params: Recordable) {
  return request.get("login_log", { params });
}

/**
 * 清空登录日志
 */
export function clear() {
  return request.delete("login_log/clear");
}

/**
 * 批量删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("login_log/delete", { id });
}

/**
 * 导出
 */
export function exportData() {
  return request.post("login_log/export", null, { responseType: "blob" });
}

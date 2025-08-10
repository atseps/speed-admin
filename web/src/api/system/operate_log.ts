import request from "@/utils/request";

/**
 * 获取操作日志列表
 * @param params
 */
export function getOperateLogList(params: Recordable) {
  return request.get("operate_log", { params });
}

/**
 * 清空操作日志
 */
export function clear() {
  return request.delete("operate_log/clear");
}

/**
 * 批量删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.post("operate_log/delete", { id });
}

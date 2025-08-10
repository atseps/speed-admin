import request from "@/utils/request";

/**
 * 获取所有数据表
 * @param params
 */
export function getAllTable(params: Recordable) {
  return request.get("generator/getAllTable", { params });
}

/**
 * 获取代码生成器列表
 * @param params
 */
export function getList(params?: Recordable) {
  return request.get("generator", { params });
}

/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request.post("generator", data);
}

/**
 * 更新
 * @param data
 */
export function update(data: Recordable) {
  return request.put(`generator/${data.id}`, data);
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string) {
  return request.delete(`generator/${id}`);
}

/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`generator/${id}/edit`);
}

/**
 * 生成代码
 * @param id
 */
export function makeCode(id: string) {
  return request.post(`generator/makeCode/${id}`);
}

/**
 * 预览代码
 * @param id
 */
export function preview(id: string) {
  return request.get(`generator/preview/${id}`);
}

/**
 * 删除字段
 * @param id
 */
export function deleteFiled(id: string) {
  return request.delete(`generator/deleteFiled/${id}`);
}

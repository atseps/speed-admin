import request from "@/utils/request";

/**
 * 获取部门列表
 * @param params
 */
export function getDeptList(params?: Recordable) {
  return request.get('department', { params });
}

/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `department/${data.id}` : "department",
    method: data.id ? "put" : "post",
    data: data
  });
}

/**
 * 删除部门
 * @param params
 */
export function destroy(id: string) {
  return request.delete(`department/${id}`);
}

/**
 * 获取修改的数据
 * @param params
 */
export function getEdit(id: string) {
  return request.get(`department/${id}/edit`);
}

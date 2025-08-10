import request from "@/utils/request";

/**
 * 获取角色列表
 * @param params
 */
export function getRoleList(params: Recordable) {
  return request.get("role", { params });
}

/**
 * 获取所有角色
 */
export function getRoleAll() {
  return request.get("role/all");
}

/**
 * 获取角色权限
 * @param id
 */
export function getTreeAuth(id: string) {
  return request.get("/authAccess", { params: { id } });
}

/**
 * 保存角色权限
 * @param data
 */
export function saveAuth(data: Recordable) {
  return request.post("/authAccess", data);
}

/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `role/${data.id}` : "role",
    method: data.id ? "put" : "post",
    data: data
  });
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string) {
  return request.delete(`role/${id}`);
}

/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string) {
  return request.get(`role/${id}/edit`);
}

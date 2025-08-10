import request from "@/utils/request";

/**
 * 获取用户列表
 * @param params
 */
export function getUserList(params: Recordable) {
  return request.get("user", { params });
}

/**
 * 获取激活的用户
 * @param params
 */
export function getActiveUsers(params: Recordable) {
  return request.get("user/getActiveUsers", { params });
}

/**
 * 根据id获取用户
 * @param id
 */
export function getUserById(id: string | number | string[] | number[]) {
  return request.get("user/getUserById", { params: { id } });
}

/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  const url = data.id ? `user/${data.id}` : "user";
  const method = data.id ? "put" : "post";
  return request[method](url, data);
}

/**
 * 获取修改的数据
 * @param id
 */
export function getEdit(id: string | number) {
  return request.get(`user/${id}/edit`);
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string | number) {
  return request.delete(`user/${id}`);
}

/**
 * 修改状态
 * @param id
 */
export function changeStatus(id: string | number) {
  return request.put(`user/changeStatus/${id}`);
}

/**
 * 重置密码
 * @param id
 */
export function resetPassword(id: string | number) {
  return request.put(`user/resetPassword/${id}`);
}

/**
 * 更新个人信息
 * @param data
 */
export function updateInfo(data: Recordable) {
  return request.put("user/updateInfo", data);
}

/**
 * 修改密码
 * @param data
 */
export function changePassword(data: Recordable) {
  return request.put("user/changePassword", data);
}

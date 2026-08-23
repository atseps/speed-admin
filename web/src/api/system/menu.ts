import request from "@/utils/request";

/**
 * 获取菜单列表
 * @param params
 */
export function getMenuList(params?: Recordable) {
  return request.get("menu", { params });
}

/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `menu/${data.id}` : "menu",
    method: data.id ? "put" : "post",
    data: data
  });
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string) {
  return request.delete(`menu/${id}`);
}

/**
 * 同层拖动排序 - 批量更新菜单 sort 字段
 * @param data
 */
export function updateSort(data: Recordable[]) {
  return request.post("menu/updateSort", data);
}

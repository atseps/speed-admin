import request from "@/utils/request";

/**
 * 获取字典列表
 * @param params
 */
export function getDictList(params?: Recordable) {
  return request.get('dict', { params });
}

/**
 * 保存
 * @param data
 */
export function save(data: Recordable) {
  return request({
    url: data.id ? `dict/${data.id}` : "dict",
    method: data.id ? "put" : "post",
    data: data
  });
}

/**
 * 删除
 * @param id
 */
export function destroy(id: string) {
  return request.delete(`dict/${id}`);
}

/**
 * 更改状态
 * @param id
 */
export function changeStatus(id: string) {
  return request.post(`dict/changeStatus/${id}`);
}


/**
 * 更新字典缓存
 */
export function updateCache() {
  return request.post("/dict/updateCache");
}

/**
 * 更新字典排序
 * @param data
 */
export function updateSort(data: Object) {
  return request.post("/dict/updateSort", data);
}


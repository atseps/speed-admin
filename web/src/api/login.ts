import request from "@/utils/request";
import { getRefreshToken } from "@/utils/auth";
/**
 * 登录
 * @param data
 */
export function login(data: Recordable) {
  return request.post("login", data);
}

/**
 * 退出登录
 * @param data
 */
export function logout() {
  return request.post("logout", { refreshToken: getRefreshToken() });
}

/**
 * 获取用户信息
 * @param data
 */
export function getUserInfo() {
  return request.get("getUserInfo");
}

/**
 * 获取菜单路由
 */
export function getRouter() {
  return request.get("getRouter");
}

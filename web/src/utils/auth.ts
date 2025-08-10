import storage from "./storage";
import { resetRouter } from "@/router";
const ACCESS_TOKEN_KEY = "access_token";
const REFRESH_TOKEN_KEY = "refresh_token";
const TOKEN_PREFIX = "Bearer ";

export function getAccessToken() {
  return storage.get(ACCESS_TOKEN_KEY);
}

export function setAccessToken(token: string, expire: number | null = null) {
  return storage.set(ACCESS_TOKEN_KEY, TOKEN_PREFIX + token, expire);
}

export function removeAccessToken() {
  return storage.remove(ACCESS_TOKEN_KEY);
}

export function getRefreshToken() {
  return storage.get(REFRESH_TOKEN_KEY);
}

export function setRefreshToken(token: string, expire: number | null = null) {
  return storage.set(REFRESH_TOKEN_KEY, TOKEN_PREFIX + token, expire);
}

export function removeRefreshToken() {
  return storage.remove(REFRESH_TOKEN_KEY);
}

export function clearToken() {
  removeRefreshToken();
  removeAccessToken();
}

export function clearAuth() {
  clearToken();
  resetRouter();
}

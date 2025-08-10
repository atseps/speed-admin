import axios from "axios";
import notification from "ant-design-vue/es/notification";
import { getAccessToken, getRefreshToken } from "@/utils/auth";
import { useUserStore } from "@/store/modules/user";
import router from "@/router";

// 创建 axios 实例
const http = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  timeout: 60000 // 请求超时时间60s
});

const responseNotice = (title: string, description?: string, duration = 2) => {
  notification.error({
    message: title,
    description: description,
    duration: duration
  });
};

const errorHandler = (error: Recordable) => {
  const store = useUserStore();
  notification.destroy();
  const { status, config, data } = error.response;
  const url = config?.url.split(config.baseURL)[1] || config.url;
  const message = data?.msg || data?.message;
  if (status === 401) {
    const currentRoute = router.currentRoute.value;
    const whiteList = ["/", "/login"];
    if (!whiteList.includes(currentRoute.path)) {
      responseNotice(message);
    }
    store.logout(false);
    return Promise.reject(error.response);
  } else {
    responseNotice(message, `${status}：${url}`, 3);
  }
  return Promise.reject(error);
};

// request 拦截器
http.interceptors.request.use((config: Recordable) => {
  const token = getAccessToken();
  if (token) {
    config.headers = {
      Authorization: token,
      ...config.headers
    };
  }
  return config;
}, errorHandler);

// response 拦截器
http.interceptors.response.use(response => {
  if (response.config.responseType === "blob") {
    return response;
  }

  // 登录过期 刷新 token 重新请求
  if (response.data.code === 401) {
    return refreshToken(response);
  }

  return response.data;
}, errorHandler);

// 令牌是否正在刷新
let refreshing = false;
// 请求队列
let requests: Array<Fn> = [];

//令牌刷新
const refreshToken = (response: Recordable) => {
  const config = response.config;
  const store = useUserStore();
  const refreshToken = getRefreshToken();
  if (!refreshing && refreshToken) {
    refreshing = true;
    return http({
      url: "refreshToken",
      method: "get",
      headers: {
        Authorization: refreshToken
      }
    })
      .then(({ data }) => {
        store.setToken(data);
        config.headers["Authorization"] = getAccessToken();
        // 请求出队列
        requests.forEach((cb: Fn) => cb());
        requests = [];
        return http(config);
      })
      .catch((err) => {
        requests = [];
        store.logout(false);
        return Promise.reject(err);
      })
      .finally(() => {
        if (requests.length > 0) {
          requests.forEach((cb: Fn) => cb());
          requests = [];
        }
        refreshing = false;
      });
  } else {
    return new Promise(resolve => {
      // 将resolve放进队列，用一个函数形式来保存，等token刷新后直接执行
      requests.push(() => {
        config.headers["Authorization"] = getAccessToken();
        resolve(http(config));
      });
    });
  }
};

export default http;

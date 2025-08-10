import * as loginApi from "@/api/login";
import * as auth from "@/utils/auth";
import router from "@/router";
import { defineStore } from "pinia";
interface UserState {
  accessToken: string;
  refreshToken: string;
  roles: Array<string>;
  userInfo: {
    id?: string | number;
    avatar?: string;
    realname?: string;
    username?: string;
    role_name?: string;
    department_name?: string;
  };
  rules: Array<string>;
}
interface Tokens {
  access_token: string;
  refresh_token: string;
  expires_in: number;
}

export const useUserStore = defineStore("user", {
  state: (): UserState => ({
    accessToken: auth.getAccessToken(),
    refreshToken: auth.getRefreshToken(),
    roles: [],
    userInfo: {},
    rules: []
  }),
  actions: {
    login(userInfo: Recordable): Promise<ResponseBody> {
      return new Promise((resolve, reject) => {
        loginApi.login(userInfo)
          .then(response => {
            const { data } = response;
            if(data){
              this.setToken(data)
            }
            resolve(response);
          })
          .catch(error => {
            reject(error);
          });
      });
    },
    async logout(callApi = true): Promise<void> {
      try {
        callApi && await loginApi.logout()
      } finally {
        this.clearState();
        auth.clearAuth()
        router.push('/login')
      }
    },
    clearState() {
      this.roles = [];
      this.rules = []
      this.userInfo = {};
      this.accessToken = '';
      this.refreshToken = '';
    },
    setToken(data: Tokens) {
      const { access_token, refresh_token } = data;
      if (access_token) {
        auth.setAccessToken(access_token);
        this.accessToken = access_token;
      }
      if (refresh_token) {
        auth.setRefreshToken(refresh_token);
        this.refreshToken = refresh_token;
      }
    },
    getUserInfo() {
      return new Promise((resolve, reject) => {
        loginApi.getUserInfo()
          .then((res: Recordable) => {
            const result = res.data;
            if (res.code == 1) {
              this.roles = result.roles;
              this.rules = result.rules;
              this.userInfo = result;
            } else {
              reject(new Error("getUserInfo: Failed to get user information !"));
            }
            resolve(result);
          })
          .catch(error => {
            reject(error);
          });
      });
    }
  }
});

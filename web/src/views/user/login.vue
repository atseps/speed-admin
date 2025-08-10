<template>
  <div class="flex h-screen w-full overflow-hidden">
    <div class="w-[50%] login-bg relative hidden xl:block">
      <div class="logo absolute top-3 left-3">
        <img src="@/assets/logo.svg" alt="logo" class="w-[35px] h-[35px]" />
        <span class="ml-3 text-black">{{ title }}</span>
      </div>
      <div class="w-full h-full flex justify-center items-center">
        <img src="@/assets/images/login-box2.svg" class="w-[60%]" />
      </div>
    </div>
    <div class="flex-1 flex items-center justify-center bg-white dark:bg-gray-800">
      <div class="md:w-[400px] w-full p-6 md:py-12 md:p-0 rounded-lg">
        <div class="mb-2 xl:text-2xl dark:text-white">欢迎登录系统</div>
        <p style="font-size: 14px; color: #99a1b7" class="mb-6">请输入您的账号密码进行登录</p>
        <a-form
          ref="form"
          :model="state.form"
          class="login-form"
          :rules="state.rules"
          @finish="submitForm"
        >
          <a-form-item name="username">
            <a-input
              size="large"
              allowClear
              v-model:value="state.form.username"
              placeholder="请输入用户名"
            >
              <template #prefix>
                <s-icon type="UserOutlined" />
              </template>
            </a-input>
          </a-form-item>

          <a-form-item name="password">
            <a-input-password
              size="large"
              v-model:value="state.form.password"
              placeholder="请输入密码"
            >
              <template #prefix>
                <s-icon type="LockOutlined" />
              </template>
            </a-input-password>
          </a-form-item>
          <a-form-item>
            <a-checkbox v-model:checked="state.remAccount">记住账号</a-checkbox>
            <a style="float: right">忘记密码</a>
          </a-form-item>
          <a-button
            :loading="state.loading"
            type="primary"
            size="large"
            htmlType="submit"
            class="w-full mb-[30px]"
            >登录</a-button
          >
        </a-form>
        <p class="text-gray-500 text-xs mt-4 text-center">Copyright © {{copyright.company}}</p>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useUserStore } from "@/store/modules/user";
import { useRouter } from "vue-router";
import config from "@/config";
import storage from "@/utils/storage";
const { message } = useMessage();
const title = computed(() => config.appName);
const copyright = computed(() => config.copyright);
const state = reactive({
  form: {
    username: "",
    password: ""
  },
  rules: {
    username: [{ required: true, message: "请输入用户名" }],
    password: [{ required: true, message: "请输入密码" }]
  },
  remAccount: false,
  loading: false
});

const userStore = useUserStore();

//提交表单
const submitForm = () => {
  state.loading = true;
  userStore
    .login(state.form)
    .then(res => {
      res.code == 1 ? loginSuccess() : loginFail(res);
    })
    .catch(res => loginFail(res))
};

const route = useRouter();

const loginSuccess = () => {
  storage.set("account", {
    remember: state.remAccount,
    username: state.remAccount ? state.form.username : ""
  });
  route.push({ path: "/" });
  state.loading = false;
};

const loginFail = (res: ResponseBody) => {
  state.loading = false;
  message.error(res.msg, 1.5);
};

onMounted(() => {
  const data = storage.get("account");
  if (data?.remember) {
    state.remAccount = data.remember;
    state.form.username = data.username;
  }
});
</script>

<style scoped lang="less">
.login-bg {
  background: #e9efff;
  background-size: 100% 100%;
  background-repeat: no-repeat;
}
:deep(.ant-form-item-control-input-content) {
  border-radius: var(--ant-border-radius);
}

:deep(.ant-input-affix-wrapper) {
  background: #f3f3f3;
  @apply dark:bg-black;
  border: none;
  outline: none;
  box-shadow: none;
}
:deep(.ant-input) {
  background: #f3f3f3;
  @apply dark:bg-transparent;
}
:deep(.ant-input-affix-wrapper-status-error) {
  .ant-input-prefix {
    color: inherit !important;
  }
  &.ant-input-affix-wrapper,
  .ant-input {
    background: #ffece8;
    @apply dark:bg-black;
  }
}
</style>

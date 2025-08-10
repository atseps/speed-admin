<template>
  <a-form :model="form" layout="vertical" class="edit-pwd" :rules="rules" @finish="onFinish">
    <a-form-item label="原密码" name="password_old">
      <a-input-password v-model:value="form.password_old" placeholder="请输入原密码" />
    </a-form-item>
    <a-form-item label="新密码" name="password">
      <a-input-password v-model:value="form.password" placeholder="请输入新密码" />
    </a-form-item>
    <a-form-item label="确认密码" name="password_confirm">
      <a-input-password v-model:value="form.password_confirm" placeholder="请输入新密码" />
    </a-form-item>
    <a-form-item>
      <a-button type="primary" class="mt-[20px]" :loading="loading" html-type="submit">
        保存
      </a-button>
    </a-form-item>
  </a-form>
</template>
<script setup lang="ts">
import { changePassword } from "@/api/system/user";
import { useUserStore } from "@/store/modules/user";
import { logout } from "@/api/login";
import * as auth from "@/utils/auth";
const { message, createSuccessModal } = useMessage();
const router = useRouter();
const store = useUserStore();

const form = reactive({
  password_old: "",
  password: "",
  password_confirm: ""
});

const rules = reactive({
  password_old: [{ required: true, message: "请输入原密码" }],
  password: [{ required: true, message: "请输入新密码" }],
  password_confirm: [{ required: true, message: "请输入确认密码" }]
});
const loading = ref(false);

function onFinish() {
  if (form.password != form.password_confirm) {
    message.error("两次输入密码不一致");
    return;
  }
  loading.value = true;
  changePassword(form)
    .then(async res => {
      if (res.code == 1) {
        await logout();
        store.clearState()
        auth.clearAuth()
        notification(res.msg);
      } else {
        message.error(res.msg, 1);
      }
    })
    .finally(() => {
      loading.value = false;
    });
}

function notification(msg: string) {
  createSuccessModal({
    title: msg,
    content: "密码修改成功，您即将重新登录",
    onOk: () => {
      router.push("/login");
    }
  });
}
</script>

<style lang="less">
.edit-pwd {
  max-width: 500px;
}
</style>

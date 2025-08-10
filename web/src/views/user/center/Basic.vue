<template>
  <a-row :gutter="16">
    <a-col :md="24" :lg="16">
      <a-form :model="form" layout="vertical" :rules="rules" @finish="onFinish">
        <a-form-item label="用户姓名" name="realname">
          <a-input v-model:value="form.realname" placeholder="请输入用户姓名" />
        </a-form-item>
        <a-form-item label="手机号码">
          <a-input v-model:value="form.phone" placeholder="请输入手机号码" />
        </a-form-item>
        <a-form-item label="用户邮箱">
          <a-input v-model:value="form.email" placeholder="请输入用户邮箱" />
        </a-form-item>
        <a-form-item :colon="false">
          <a-button type="primary" class="mt-[20px]" :loading="loading" html-type="submit">
            保存
          </a-button>
        </a-form-item>
      </a-form>
    </a-col>
    <a-col :md="24" :lg="8" style="min-height: 180px; padding-left: 60px">
      <s-upload show-type="avatar" v-model:fileUrl="form.avatar"></s-upload>
    </a-col>
  </a-row>
</template>
<script setup lang="ts">
import { useUserStore } from "@/store/modules/user";
import { storeToRefs } from "pinia";
import { updateInfo } from "@/api/system/user";
import { keys, pick } from "lodash";

const { message } = useMessage();
const store = useUserStore();
const { userInfo } = storeToRefs(store);
const form = reactive({
  realname: "",
  phone: "",
  email: "",
  avatar: ""
});
const rules = reactive({
  realname: [{ required: true, message: "请输入用户姓名" }]
});
const loading = ref(false);

onMounted(() => {
  const data = pick(unref(userInfo), keys(form));
  Object.assign(form, data);
});

function onFinish() {
  loading.value = true;
  updateInfo(form)
    .then(res => {
      if (res.code == 1) {
        message.success(res.msg, 1);
        store.getUserInfo();
      } else {
        message.error(res.msg, 1);
      }
    })
    .finally(() => {
      loading.value = false;
    });
}
</script>

<style lang="less" scoped>
.mobile {
  .info-card {
    margin-left: 0px;
  }
}

.info-card {
  margin-left: 30px;
}
.info-list {
  margin: 20px 10px;
  margin-bottom: 0px;
  padding: 20px 20px;
}
.avatar-upload-wrapper {
  height: 200px;
  width: 100%;
}

.ant-upload-preview {
  position: relative;
  margin: 0 auto;
  width: 100%;
  max-width: 150px;
  border-radius: 50%;
  box-shadow: 0 0 4px #ccc;
  .upload-icon {
    position: absolute;
    top: 0;
    right: 10px;
    font-size: 1.4rem;
    padding: 0.5rem;
    background: rgba(222, 221, 221, 0.7);
    border-radius: 50%;
    border: 1px solid rgba(0, 0, 0, 0.2);
  }
  .mask {
    opacity: 0;
    position: absolute;
    background: rgba(0, 0, 0, 0.4);
    cursor: pointer;
    transition: opacity 0.4s;

    &:hover {
      opacity: 1;
    }

    i {
      font-size: 2rem;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -1rem;
      margin-top: -1rem;
      color: #d6d6d6;
    }
  }

  img,
  .mask {
    width: 100%;
    max-width: 150px;
    height: 100%;
    border-radius: 50%;
    overflow: hidden;
  }
}
</style>

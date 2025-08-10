<template>
  <s-modal
    v-bind="$attrs"
    @register="register"
    :footer="null"
    destroyOnClose
    :width="470"
    :maskClosable="false"
    title="重置密码"
    @cancel="close"
  >
    <a-result status="success" title="密码重置成功" :subTitle="`${time}秒后自动关闭`">
      <div class="desc">
        <p>新密码: {{ password }}</p>
        <p>请您尽快更改密码，以免造成密码泄露</p>
      </div>
    </a-result>
  </s-modal>
</template>

<script setup lang="ts">
import { useModalInner } from "@/components/Modal";
const password = ref(undefined);
let timeId: number | undefined;
const time = ref(0);

const [register, { closeModal }] = useModalInner(data => {
  password.value = data.password;
  time.value = 30;
  countDown();
});

function close() {
  clearInterval(timeId);
  closeModal();
  time.value = 0;
}

function countDown() {
  timeId = setInterval(() => {
    time.value--;
    if (time.value <= 0) {
      close();
    }
  }, 1000);
}
</script>

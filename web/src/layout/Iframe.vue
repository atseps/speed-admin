<template>
  <div class="bg-[var(--bg-color)] ant-pro-iframe-wrap">
    <a-spin
      :spinning="loading"
      wrapper-class-name="b-rd-8px of-hidden w-full h-full flex flex-col flex-1"
    >
      <iframe
        class="w-full h-full flex flex-col flex-1"
        :src="url"
        style="border: none"
        @load="finishLoading"
      />
    </a-spin>
  </div>
</template>
<script setup lang="ts">
import { useAppStore } from "@/store/modules/app";
const route = useRoute();
const appStore = useAppStore();
const url = computed(() => route.meta?.url as string | undefined);
const loading = ref(true);
function finishLoading() {
  loading.value = false;
}

onMounted(() => {
  appStore.showFooter = false;
});

onBeforeUnmount(() => {
  appStore.showFooter = true;
});
</script>
<style lang="less">
.ant-pro-iframe-wrap {
  height: 100%;
  .ant-spin-container {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    flex: 1;
  }
}
</style>

<template>
  <div class="layout-footer">
    <div class="copyright">
      Copyright © {{ dateYear }}
      <a :href="copyright.link" target="_blank" class="cursor-pointer">{{ copyright.company }}</a>
      <a-divider type="vertical" v-if="copyright.icp" />
      <a href="https://beian.miit.gov.cn" target="_blank">{{ copyright.icp }}</a>
      <a-divider type="vertical" />
       v {{ config.version}}
    </div>
  </div>
</template>

<script setup lang="ts">
import config from "@/config";
const copyright = ref(config.copyright);
const dateYear = computed(() => {
  const date = new Date();
  return date.getFullYear();
});
</script>

<style lang="less" scoped>
.layout-footer {
  padding: 0 16px;
  margin: 10px 0;
  text-align: center;

  .links {
    margin-bottom: 8px;

    a {
      color: rgba(0, 0, 0, 0.45);

      &:hover {
        color: rgba(0, 0, 0, 0.65);
      }

      &:not(:last-child) {
        margin-right: 40px;
      }
    }
  }

  .copyright,
  .copyright a {
    color: #888;
    font-size: 12px;
  }
}
</style>

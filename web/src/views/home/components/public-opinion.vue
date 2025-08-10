<template>
  <a-card class="general-card" title="数据分析" :header-style="{ paddingBottom: '12px' }">
    <a-row :gutter="16">
      <a-col v-bind="span">
        <chain-item
          title="访问总人次"
          quota="visitors"
          chart-type="line"
          :card-style="{
            background: isRealDark
              ? 'linear-gradient(180deg, #284991 0%, #122B62 100%)'
              : 'linear-gradient(180deg, #f2f9fe 0%, #e6f4fe 100%)'
          }"
        />
      </a-col>
      <a-col v-bind="span">
        <chain-item
          title="内容发布量"
          quota="published"
          chart-type="bar"
          :card-style="{
            background: isRealDark
              ? ' linear-gradient(180deg, #3D492E 0%, #263827 100%)'
              : 'linear-gradient(180deg, #F5FEF2 0%, #E6FEEE 100%)'
          }"
        />
      </a-col>
      <a-col v-bind="span">
        <chain-item
          title="评论总量"
          quota="comment"
          chart-type="line"
          :card-style="{
            background: isRealDark
              ? 'linear-gradient(180deg, #294B94 0%, #0F275C 100%)'
              : 'linear-gradient(180deg, #f2f9fe 0%, #e6f4fe 100%)'
          }"
        />
      </a-col>
      <a-col v-bind="span">
        <chain-item
          title="分享总量"
          quota="share"
          chart-type="pie"
          :card-style="{
            background: isRealDark
              ? 'linear-gradient(180deg, #312565 0%, #201936 100%)'
              : 'linear-gradient(180deg, #F7F7FF 0%, #ECECFF 100%)'
          }"
        />
      </a-col>
    </a-row>
  </a-card>
</template>

<script lang="ts" setup>
import ChainItem from "./chain-item.vue";
import { useAppStore } from "@/store/modules/app";
const appStore = useAppStore();
const span = reactive({ xs: 12, sm: 12, md: 12, lg: 12, xl: 6, xxl: 6 });
const isRealDark = computed(() => {
  return appStore.theme === "realDark";
});
</script>

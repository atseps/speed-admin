<template>
  <page-view :card="false">
    <div class="space-unit mb-4">
      <PublicOpinion />
    </div>
    <div class="mb-4">
      <a-row :gutter="16">
        <a-col v-bind="{ xs: 24, sm: 24, md: 24, lg: 24, xl: 16, xxl: 16 }">
          <ContentPublishRatio />
        </a-col>
        <a-col v-bind="{ xs: 24, sm: 24, md: 24, lg: 24, xl: 8, xxl: 8 }">
          <PopularAuthor />
        </a-col>
      </a-row>
    </div>
    <div>
      <ContentPeriodAnalysis />
    </div>
  </page-view>
</template>
<script lang="ts" setup>
import PublicOpinion from "./components/public-opinion.vue";
import ContentPeriodAnalysis from "./components/content-period-analysis.vue";
import ContentPublishRatio from "./components/content-publish-ratio.vue";
import PopularAuthor from "./components/popular-author.vue";
</script>
<style lang="less">
.general-card {
  border-radius: 4px;
  border: none;
  & > .ant-card-head {
    height: auto;
    border: none;
  }
  & > .ant-card-body {
    padding: 0 20px 20px 20px;
  }
}
//图表样式
.echarts-tooltip-diy {
  background: linear-gradient(
    304.17deg,
    rgba(253, 254, 255, 0.6) -6.04%,
    rgba(244, 247, 252, 0.6) 85.2%
  );
  border: none;
  backdrop-filter: blur(10px);
  /* Note: backdrop-filter has minimal browser support */

  border-radius: 6px;
  .content-panel {
    display: flex;
    justify-content: space-between;
    padding: 0 9px;
    background: rgba(255, 255, 255, 0.8);
    width: 164px;
    height: 32px;
    line-height: 32px;
    box-shadow: 6px 0px 20px rgba(34, 87, 188, 0.1);
    border-radius: 4px;
    margin-bottom: 4px;
  }
  .tooltip-title {
    margin: 0 0 10px 0;
  }
  p {
    margin: 0;
  }
  .tooltip-title,
  .tooltip-value {
    font-size: 13px;
    line-height: 15px;
    display: flex;
    align-items: center;
    text-align: right;
    color: #1d2129;
    font-weight: bold;
  }
  .tooltip-item-icon {
    display: inline-block;
    margin-right: 8px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
  }
}
</style>

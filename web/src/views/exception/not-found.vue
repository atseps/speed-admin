<template>
  <a-result
    status="404"
    title="很抱歉，您尝试访问的组件未能找到"
    sub-title="组件加载出错，请检查路径是否有误"
  >
    <template #extra>
      <router-link :to="{ path: '/' }">
        <a-button type="primary">返回首页</a-button>
      </router-link>
    </template>
  </a-result>
</template>

<template>
  <div class="logo" :style="{ padding: padding }">
    <router-link :to="{ path: '/' }">
      <img src="@/assets/logo.svg" />
      <h1 v-if="showTitle">{{ title }}</h1>
    </router-link>
  </div>
</template>
<script setup lang="ts">
import { useSetting } from "@/layout/hooks";
import config from "@/config";
const props = defineProps({
  showTitle: {
    type: Boolean,
    default: true
  }
});
const { isMinxLayout } = useSetting();

const title = computed(() => {
  return config.appName;
});

const padding = computed(() => {
  if (unref(isMinxLayout)) {
    return "8px 16px";
  }
  return props.showTitle ? "16px" : "16px 8px";
});
</script>

<style lang="less" scoped>
.sider.light .logo {
  box-shadow: none;
}

.logo {
  position: relative;
  display: flex;
  align-items: center;
  line-height: 32px;
  cursor: pointer;

  a {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 32px;
  }

  img {
    display: inline-block;
    height: 30px;
    vertical-align: middle;
    transition: height 0.2s;
  }

  h1 {
    display: inline-block;
    height: 32px;
    margin: 0 0 0 12px;
    overflow: hidden;
    color: #fff;
    font-weight: 600;
    font-size: 18px;
    line-height: 32px;
    vertical-align: middle;
    animation-duration: 0.2s;
  }
}
</style>

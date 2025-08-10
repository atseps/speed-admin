<template>
  <div class="table-settings">
    <RefreshSetting />
    <FullScreenSetting />
    <SizeSetting />
    <ColumnSetting />
  </div>
</template>
<script lang="ts" setup>
import RefreshSetting from "./RefreshSetting.vue";
import FullScreenSetting from "./FullScreenSetting.vue";
import SizeSetting from "./SizeSetting.vue";
import ColumnSetting from "./ColumnSetting.vue";
</script>
<style lang="less">
.table-settings {
  & > .anticon {
    margin-right: 16px;
  }
  svg {
    width: 1.3em;
    height: 1.3em;
  }
}
</style>

<template>
  <s-drawer v-bind="$attrs" :title="title" width="50%" showFooter> Drawer Info. </s-drawer>
</template>
<script lang="ts" setup>
const title = ref("Drawer Title");
</script>

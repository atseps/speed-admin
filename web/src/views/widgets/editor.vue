<template>
  <page-view>
    <a-card class="mb-4" title="富文本编辑器">
      <SEditor v-model="value"></SEditor>
    </a-card>
  </page-view>
</template>
<script lang="ts" setup>
const value = ref("");
</script>

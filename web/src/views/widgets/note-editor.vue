<template>
  <page-view>
    <a-card class="mb-4" title="文本域编辑器">
      <s-note-editor v-model="value" />
    </a-card>
    <a-card class="mb-4" title="默认值">
      <s-note-editor v-model="text" :fileList="fileList" />
    </a-card>
  </page-view>
</template>
<script lang="ts" setup>
const value = ref("");
const text = ref("默认文本");
const fileList = ref([
  {
    url: "https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.png",
    name: "IMG.png",
    uid: "112"
  },
  {
    url: "https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.docx",
    name: "订单.docx",
    uid: "112"
  }
]);
</script>

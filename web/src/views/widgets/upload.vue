<template>
  <page-view>
    <a-card class="mb-4" title="头像上传">
      <SUpload show-type="avatar" v-model:fileUrl="avatarUrl"></SUpload>
    </a-card>

    <a-card class="mb-4" title="图片上传">
      <span>单张图片：</span>
      <SUpload :multiple="false" show-type="image" v-model:fileUrl="imageUrl"></SUpload>
      <span>多张图片：</span>
      <SUpload show-type="image" v-model:fileUrl="multipleImgUrl"></SUpload>
    </a-card>

    <a-card class="mb-4" title="文件上传">
      <SUpload show-type="button" v-model:fileList="buttonUrl"></SUpload>
    </a-card>

    <a-card title="弹窗上传">
      <SUploadModal class="mb-[10px]" v-model:fileList="fileList"></SUploadModal>
    </a-card>
  </page-view>
</template>
<script lang="ts" setup>
const imageUrl = ref("");
const multipleImgUrl = ref("");
const avatarUrl = ref();
const buttonUrl: any = ref([]);
const fileList = ref([]);
setTimeout(() => {
  imageUrl.value = "https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.png";
  multipleImgUrl.value ="https://gw.alipayobjects.com/zos/rmsportal/BiazfanxmamNRoxxVxka.png,https://zos.alipayobjects.com/rmsportal/jkjgkEfvpUPVyRjUImniVslZfWPnJuuZ.png";
  avatarUrl.value = "https://gw.alipayobjects.com/zos/rmsportal/BiazfanxmamNRoxxVxka.png";
  buttonUrl.value = [
    {
      id: 630,
      url: "https://www.baidu.com",
      name: "测试文档.xls",
    }
  ];
}, 500);
</script>

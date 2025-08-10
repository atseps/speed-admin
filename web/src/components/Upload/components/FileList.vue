<template>
  <div class="file-list-item">
    <div class="file-item-meta">
      <a-image v-if="file.thumbUrl" class="file-item-avatar" :src="file.thumbUrl"></a-image>
      <s-icon v-else color="#3290f9" :type="getIconByFileType(file.name)" class="file-icon" />
      <div class="file-item-content ml-[10px]">
        <div class="file-text">
          <h4 class="file-item-title" :title="file.name">{{ file.name }}</h4>
          <span class="file-size">({{ fileSizeFormat(file.size) }})</span>
        </div>
        <a-progress :percent="file.percent" :status="progressStatus" />
      </div>
    </div>
    <div class="content mr-[20px]">
      <a-tag v-if="!file.status" color="blue">待上传</a-tag>
      <a-tag v-if="file.status == 'uploading'" color="processing">上传中</a-tag>
      <a-tag v-if="file.status == 'success'" color="success">已上传</a-tag>
      <a-tag v-if="file.status == 'error'" color="error">上传失败</a-tag>
    </div>
    <div class="file-item-action mr-[20px]">
      <a-button type="link" @click="onRemove(file)" danger>删除</a-button>
    </div>
  </div>
</template>

<script setup lang="tsx">
import { useModalContext } from "@/components/Modal/hooks/useModalContext";
import type { FileItem } from "../types";
import { isNumber } from "@/utils/is";
import { getIconByFileType } from "./helper";
const props = defineProps({
  file: {
    type: Object as PropType<FileItem>,
    required: true
  }
});

const emit = defineEmits(["remove"]);

const fileSizeFormat = (cellValue: number) => {
  const size = isNumber(cellValue) ? cellValue : parseInt(cellValue);
  if (size < 1024) {
    return size.toFixed(0) + " bytes";
  } else if (size < 1024 * 1024) {
    return (size / 1024.0).toFixed(0) + " KB";
  } else if (size < 1024 * 1024 * 1024) {
    return (size / 1024.0 / 1024.0).toFixed(1) + " MB";
  } else {
    return (size / 1024.0 / 1024.0 / 1024.0).toFixed(1) + " GB";
  }
  return "";
};

const progressStatus = computed(() => {
  const { status } = props.file;
  if (status == "error") {
    return "exception";
  } else if (status == "uploading") {
    return "active";
  } else {
    return "normal";
  }
});

const onRemove = (file: FileItem) => {
  emit("remove", file);
};

const modalFn = useModalContext();

watch(
  () => props.file,
  () => {
    nextTick(() => {
      modalFn?.redoModalHeight?.();
    });
  }
);
</script>

<style lang="less" scoped>
.file-list-item {
  display: flex;
  padding: 10px 0;
  align-items: center;
  border-block-end: 1px solid rgba(5, 5, 5, 0.06);

  .file-item-meta {
    display: flex;
    flex: 1;
    align-items: flex-start;
    max-width: 100%;
  }

  :deep(.file-item-avatar ){
    width: 45px;
    height: 45px;
    //border-radius: 50%;
  }

  .file-size {
    font-size: 14px;
    color: #999;
    margin: 0 10px;
  }

  .file-icon {
    width: 40px;
    height: 40px;
    justify-content: center;
    font-size: 40px;
    padding: 0.3rem;
  }

  .file-item-content {
    width: 310px;
  }

  .file-text {
    display: flex;
  }

  .file-item-title {
    max-width: 230px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin: 0;
  }
}
</style>

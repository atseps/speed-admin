<template>
  <div class="upload-list">
    <div class="upload-list-item" :key="item.uid" v-for="item in fileList">
      <div class="upload-list-item-mate">
        <a-image v-if="isImageUrl(item.name)" class="file-item-image" :src="item.url"></a-image>
        <s-icon v-else color="#3290f9" :type="getIconByFileType(item.name)" class="file-icon" />
        <a :href="item.url" target="_blank" class="file-item-name">{{ item.name }}</a>
        <span v-if="showRemoveIcon" class="file-item-remove">
          <s-button
            type="text"
            size="small"
            icon="DeleteOutlined"
            @click="onRemove(item)"
          ></s-button>
        </span>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { UploadFile } from "../types";
import { getIconByFileType } from "./helper";
import { isImageUrl } from "@/utils/is";
defineProps({
  fileList: {
    type: Array as PropType<UploadFile[]>,
    default: () => []
  },
  showRemoveIcon: {
    type: Boolean,
    default: true
  }
});
const emit = defineEmits(["remove"]);
const onRemove = (file: UploadFile) => {
  emit("remove", file);
};
</script>
<style scoped lang="less">
.upload-list-item {
  position: relative;
  height: 66px;
  padding: 8px;
  border-radius: 4px;
  margin-top: 8px;
  font-size: 14px;
  display: flex;
  align-items: center;
  transition: background-color 0.3s;
  background-color: #f2f5f7;
  @apply dark:bg-black;
}
:deep(.file-item-image) {
  width: 40px;
  height: 40px;
}
.file-item-name {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
  padding: 0 8px;
  line-height: 1.5714285714285714;
  flex: auto;
  transition: all 0.3s;
}

.file-icon {
  width: 40px;
  height: 40px;
  justify-content: center;
  font-size: 40px;
  padding: 0.3rem;
  flex: none;
}
.upload-list-item-mate {
  display: flex;
  align-items: center;
  width: 100%;
}
.file-item-remove {
  :deep(.anticon-delete) {
    color: rgba(0, 0, 0, 0.45);
    @apply dark:text-zinc-400;
  }
}
</style>

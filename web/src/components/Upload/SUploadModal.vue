<template>
  <div class="upload-modal">
    <s-button type="primary" icon="upload-outlined" @click="openUploadModal"> 上传 </s-button>
    <UploadDialog @register="registerUploadModal" @ok="handleOk" />
    <UploadList
      v-if="showUploadList"
      :fileList="fileItems"
      :showRemoveIcon="showRemoveIcon"
      @remove="onRemove"
    ></UploadList>
  </div>
</template>

<script setup lang="ts">
import UploadDialog from "./components/UploadDialog.vue";
import { useModal } from "@/components/Modal";
import { cloneDeep, isEqual } from "lodash-es";
import { UploadFile } from "./types";
import UploadList from "./components/UploadList.vue";
const props = defineProps({
  fileList: {
    type: Array as PropType<UploadFile[]>,
    default: () => []
  },
  showUploadList: {
    type: Boolean,
    default: true
  },
  showRemoveIcon: {
    type: Boolean,
    default: true
  }
});
const fileItems = ref<UploadFile[]>([]);

const [registerUploadModal, { openModal: openUploadModal }] = useModal();
const emit = defineEmits(["submit", "update:fileList"]);

watch(
  props.fileList,
  (newVal, odldVal) => {
    if (!isEqual(newVal, odldVal)) {
      fileItems.value = cloneDeep(newVal);
    }
  },
  {
    deep: true,
    immediate: true
  }
);
const handleOk = (fileData: UploadFile[]) => {
  fileItems.value.push(...fileData);
  emit("submit", fileData);
  emit("update:fileList", fileItems.value);
};

const onRemove = (file: UploadFile) => {
  fileItems.value = fileItems.value.filter(item => item.id !== file.id);
  emit("update:fileList", fileItems.value);
};
</script>

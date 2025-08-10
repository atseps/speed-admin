<template>
  <div class="editor-container">
    <slot name="extra"></slot>
    <div class="textarea-section">
      <a-textarea
        v-bind="$attrs"
        style="resize: none"
        :value="modelValue"
        @paste="handlePaste"
        @change="handleValueChange"
        :auto-size="{ minRows: rows, maxRows: 20 }"
      />
    </div>
    <div class="preview-section m-t-10">
      <a-upload
        name="file"
        :maxCount="maxCount"
        :multiple="multiple"
        :customRequest="customRequest"
        :action="state.imageUrl"
        v-model:fileList="state.imageList"
        listType="picture-card"
        @preview="handlePreview"
        @change="handleChange('image', $event)"
        accept="image/*"
      >
        <a-button style="display: none" ref="imageRef"></a-button>
      </a-upload>

      <a-upload
        name="file"
        :maxCount="maxCount"
        :multiple="multiple"
        :customRequest="customRequest"
        :action="state.fileUrl"
        v-model:fileList="state.fileList"
        @change="handleChange('file', $event)"
      >
        <a-button style="display: none" ref="fileRef"></a-button>
      </a-upload>
      <a-image
        :style="{ display: 'none' }"
        :preview="{
          visible,
          onVisibleChange: setVisible
        }"
        :src="previewImage"
      />
    </div>
    <div class="tool-section m-t-10">
      <template v-if="showUpload">
        <span
          class="tool-item"
          v-if="showUpload === true || showUpload.image"
          @click="onClickUpload(imageRef)"
        >
          <s-icon type="picture-outlined" />
          <span style="padding: 0 5px">图片</span>
        </span>
        <span
          class="tool-item"
          v-if="showUpload === true || showUpload.file"
          @click="onClickUpload(fileRef)"
        >
          <s-icon type="link-outlined" />
          <span style="padding: 0 5px">附件</span>
        </span>
      </template>
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { getAccessToken } from "@/utils/auth";
import { isImageUrl, isObject } from "@/utils/is";
import type { UploadChangeParam, UploadFile } from "ant-design-vue";
import request from "@/utils/request";
import { useLoading } from "@/components/Loading";
import type { Ref } from "vue";
import type { InputProps } from "ant-design-vue";
import type { Files, ShowUploadOptions } from "./types";
import { isEqual } from "lodash-es";
const { notification, message } = useMessage();

const props = defineProps({
  modelValue: [String, Number],
  rows: {
    type: [Number, String],
    default: 3
  },
  fileId: {
    type: [String, Array] as PropType<string | (string | number)[]>,
    default: ""
  },
  fileList: {
    type: Array as PropType<Files[]>,
    default: () => []
  },
  toArray: Boolean,
  multiple: {
    type: Boolean,
    default: true
  },
  maxCount: {
    type: Number,
    default: 10
  },
  showUpload: {
    type: [Object, Boolean],
    default: true,
    validator: function (value: ShowUploadOptions | boolean) {
      if (isObject(value)) {
        return Object.keys(value).includes("image") || Object.keys(value).includes("file");
      }
      return true;
    }
  }
});

const imageRef = ref();
const fileRef = ref();

const emit = defineEmits<{
  (e: "update:modelValue", value: string | number): void;
  (e: "update:fileId", value: string | (string | number)[]): void;
  (e: "update:fileList", value: Files[]): void;
}>();

const previewImage = ref<string>("");
const visible = ref<boolean>(false);
const setVisible = (value: boolean): void => {
  visible.value = value;
};
const state = reactive({
  headers: {
    authorization: getAccessToken()
  },
  imageUrl: "/upload/image",
  fileUrl: "/upload/attachment",
  fileList: [] as Files[], //附件
  imageList: [] as Files[] //图片
});

// 文件id
const fileIds = computed<string | (string | number)[]>(() => {
  const fileList = state.fileList.concat(state.imageList);
  const fileId: (string | number)[] = [];

  for (const file of fileList) {
    if (file.status === "error") continue;
    if (file.id) fileId.push(file.id);
  }

  return props.toArray ? fileId : fileId.join(",");
});

watch(
  () => props.fileList,
  value => {
    const newImageList = value.filter((file: Files) => isImageUrl(file.url as string));
    const newFileList = value.filter((file: Files) => !isImageUrl(file.url as string));

    // 仅在列表发生变化时更新
    if (!isEqual(state.imageList, newImageList) || !isEqual(state.fileList, newFileList)) {
      state.imageList = newImageList;
      state.fileList = newFileList;

      const allFiles = [...newImageList, ...newFileList];
      emit("update:fileList", allFiles);
      emit("update:fileId", unref(fileIds));
    }
  },
  { deep: true, immediate: true }
);

const handlePreview = (file: UploadFile) => {
  if (file.url) {
    previewImage.value = file.url;
    setVisible(true);
  }
};

//上传失败通知
const errorNotice = (info: UploadChangeParam) => {
  const { response } = info.file.error;
  const description =  response?.data?.message || response?.data?.msg;
  notification.open({
    class: "notice-danger",
    message: `${info.file.name} 上传失败！`,
    description: description,
    duration: 2
  });
};

const getFileList = (fileList: Files[]) => {
  return fileList.map((file: Files) => {
    if (file.response && file.response.data) {
      file.url = file.response.data.url;
      file.id = file.response.data.id;
    }
    return file;
  });
};

// 处理文件变化
const handleChange = (type: "image" | "file", info: UploadChangeParam) => {
  const { status } = info.file;
  const fileList = getFileList(info.fileList);
  type == "image" ? (state.imageList = fileList) : (state.fileList = fileList);
  if (status === "done" || status === "removed") {
    emit("update:fileId", unref(fileIds));
    const allFileList = state.fileList.concat(state.imageList);
    if (props.multiple) {
      if (allFileList.every((file: UploadFile) => file.url)) {
        emit("update:fileList", allFileList);
      }
    } else {
      emit("update:fileList", allFileList);
    }
  }
  if (status === "error") errorNotice(info);
};

const customRequest = ({
  action,
  data,
  file,
  filename,
  onError,
  onProgress,
  onSuccess,
  withCredentials
}: Recordable) => {
  const formData = new FormData();
  if (data) {
    Object.keys(data).forEach(key => {
      formData.append(key, data[key]);
    });
  }
  formData.append(filename, file);
  request
    .post(action, formData, {
      withCredentials,
      onUploadProgress: ({ total, loaded }) => {
        onProgress({ percent: Math.round((loaded / total) * 100) }, file);
      }
    })
    .then(response => {
      onSuccess(response, file);
    })
    .catch(error => {
      notification.destroy();
      return onError(error);
    });

  return {
    abort() {
      console.log("upload progress is aborted.");
    }
  };
};

//处理输入框输入
const handleValueChange: InputProps["onChange"] = e => {
  const value = e.target.value || "";
  emit("update:modelValue", value);
};

//点击上传
const onClickUpload = (ref: Ref) => {
  const element = unref(ref);
  if (element.$el) {
    element.$el.click();
  }
};

const [openFullLoading, closeFullLoading] = useLoading({ tip: "图片上传中..." });

//粘贴图片上传
const handlePasteUpload = (file: string | Blob) => {
  const formData = new FormData();
  formData.append("file", file);
  openFullLoading();
  request
    .post(state.imageUrl, formData)
    .then(res => {
      const files = { uid: res.data.id, ...res.data };
      state.imageList.push(files);
      emit("update:fileId", unref(fileIds));
      emit("update:fileList", state.fileList.concat(state.imageList));
      message.success({ content: "图片上传成功", duration: 1.5 });
    })
    .finally(() => {
      closeFullLoading();
    });
};

//粘贴图片
const handlePaste = (e: ClipboardEvent) => {
  if (!props.showUpload) return;
  const items = e.clipboardData?.items;
  let file: File | null = null;

  if (!items || items.length === 0) {
    message.error("当前浏览器不支持粘贴");
    return;
  }

  // 搜索剪切板items
  for (let i = 0; i < items.length; i++) {
    if (items[i].type.indexOf("image") !== -1) {
      file = items[i].getAsFile();
      break;
    }
  }

  if (!file) return;
  handlePasteUpload(file);
};
</script>

<style lang="less" scoped>
.editor-container {
  padding: 10px;
  border: 1px solid var(--ant-color-border);
  background: var(--ant-color-bg-container);
  border-radius: var(--ant-border-radius);
  position: relative;

  .tool-item {
    cursor: pointer;
    color: var(--ant-color-text);
    &:hover {
      color: var(--ant-color-primary);
    }
  }

  :deep(.ant-upload-select) {
    display: none;
  }

  .textarea-section {
    textarea {
      border: none;
      box-shadow: none;
      padding: 0px;
    }
  }
}
</style>

<template>
  <s-modal
    width="600px"
    :closeFunc="handleCloseFunc"
    title="上传"
    okText="保存"
    v-bind="$attrs"
    @register="register"
    destroyOnClose
    @ok="handleOk"
    :maskClosable="false"
    :keyboard="false"
    class="upload-modal"
  >
    <div class="upload-modal-toolbar">
      <a-upload-dragger
        class="upload-dragger"
        :fileList="state.fileList"
        :beforeUpload="beforeUpload"
        name="file"
        multiple
      >
        <p class="ant-upload-drag-icon">
          <s-icon type="inbox-outlined" />
        </p>
        <p class="ant-upload-text">单击或拖动文件到此区域上传</p>
        <p class="ant-upload-hint">支持单个或批量上传文件</p>
        <template #itemRender="{ file }">
          <FileList :file="file" @remove="handleRemove"></FileList>
        </template>
      </a-upload-dragger>
    </div>
    <template #centerFooter>
      <s-button
        @click="handleStartUpload"
        color="success"
        :disabled="!getIsSelectFile"
        :loading="state.uploading"
      >
        {{ getUploadBtnText }}
      </s-button>
    </template>
  </s-modal>
</template>
<script setup lang="tsx">
import { useModalInner } from "@/components/Modal";
import FileList from "./FileList.vue";
import { checkImgType, getBase64WithFile } from "./helper";
import { buildUUID } from "@/utils/uuid";
import request from "@/utils/request";
import type { UploadProps } from "ant-design-vue";
import { type FileItem, UploadResultStatus } from "../types";
import config from "@/config";
const { message } = useMessage();

const [register, { closeModal }] = useModalInner();

const state = reactive({
  fileList: [] as FileItem[],
  visible: false,
  url: config.uploadUrl,
  uploading: false
});

const emit = defineEmits(["ok", "register", "remove"]);

const beforeUpload: UploadProps["beforeUpload"] = async (file: File) => {
  const { size, name } = file;
  const commonItem = {
    uid: buildUUID(),
    file,
    size,
    name,
    percent: 0,
    type: name.split(".").pop()
  };
  // 生成图片缩略图
  if (checkImgType(file)) {
    getBase64WithFile(file).then(({ result: thumbUrl }) => {
      state.fileList = [
        ...unref(state.fileList),
        {
          thumbUrl,
          ...commonItem
        }
      ];
    });
  } else {
    state.fileList = [...state.fileList, commonItem];
  }
  return false;
};

//点击保存
const handleOk = () => {
  if (state.uploading) {
    return message.warning("请等待文件上传后，再保存");
  }
  const fileList: Recordable[] = [];
  for (const item of state.fileList) {
    const { status, response } = item;
    if (status === "success" && response) {
      response.uid = response.id;
      fileList.push(response);
    }
  }
  // 存在一个上传成功的即可保存
  if (fileList.length <= 0) {
    return message.warning("没有上传成功的文件，无法保存");
  }
  state.fileList = [];
  closeModal();
  emit("ok", fileList);
};

const getIsSelectFile = computed(() => {
  return (
    state.fileList.length > 0 &&
    !state.fileList.every((item: FileItem) => item.status === "success")
  );
});

const getUploadBtnText = computed(() => {
  const someError = state.fileList.some((item: FileItem) => item.status === "error");
  return state.uploading ? "上传中" : someError ? "重新上传失败文件" : "开始上传";
});

//点击开始上传
const handleStartUpload = async () => {
  try {
    state.uploading = true;
    // 只上传不是成功状态的
    const uploadFileList = state.fileList.filter(item => item.status !== "success") || [];
    const data = await Promise.all(
      uploadFileList.map(item => {
        return handleFileUpload(item);
      })
    );
    state.uploading = false;
    const errorList = data.filter(item => !item.success);
    if (errorList.length > 0) throw errorList;
  } catch (e) {
    state.uploading = false;
    throw e;
  }
};

//文件上传
const handleFileUpload = async (item: FileItem) => {
  try {
    item.status = UploadResultStatus.UPLOADING;
    const formData = new FormData();
    formData.append("file", item.file);
    const ret = await request.post(state.url, formData, {
      onUploadProgress: ({ total, loaded }) => {
        const complete = ((loaded / total) * 100) | 0;
        item.percent = complete;
      }
    });
    const { data } = ret;
    item.status = UploadResultStatus.SUCCESS;
    item.response = data;
    return {
      success: true,
      error: null
    };
  } catch (e) {
    item.status = UploadResultStatus.ERROR;
    return {
      success: false,
      error: e
    };
  }
};

//移除文件
const handleRemove = (file: FileItem) => {
  const index = state.fileList.indexOf(file);
  const newFileList = state.fileList.slice();
  newFileList.splice(index, 1);
  state.fileList = newFileList;
  emit("remove", file);
};

// 点击关闭：则所有操作不保存，包括上传的
const handleCloseFunc = async () => {
  if (!state.uploading) {
    state.fileList = [];
    return true;
  } else {
    message.warning("请等待文件上传结束后操作");
    return false;
  }
};
</script>
<style lang="less">
.error-text {
  color: red;
  margin: 0 3px;
}
</style>

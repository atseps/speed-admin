<template>
  <a-upload
    v-bind="getProps"
    :customRequest="customRequest"
    @change="handleChange"
    :fileList="state.fileList"
    :class="`upload-${showType}`"
    :action="uploadUrl"
    :before-upload="handleBeforeUpload"
    name="file"
    @preview="handlePreview"
  >
    <template v-if="!$slots.default">
      <template v-if="showType == 'image'">
        <s-icon type="plus" />
        <div class="ant-upload-text">{{ uploadTextDisplay }}</div>
      </template>
      <s-button v-if="showType == 'button'" type="primary" icon="upload-outlined">
        {{ uploadTextDisplay }}
      </s-button>
      <template v-if="showType == 'avatar'">
        <div class="ant-upload-preview" v-if="state.avatarUrl">
          <div class="mask">
            <a-space>
              <s-icon type="eye-outlined" @click.stop="avatarPreview" class="upload-icon" />
              <s-icon type="delete-outlined" @click.stop="clearUploadFile" class="upload-icon" />
            </a-space>
          </div>
          <img class="avatar-url" :src="state.avatarUrl" alt="avatar" />
        </div>
        <div v-else>
          <s-icon type="loading-outlined" v-if="state.loading" />
          <s-icon type="plus-outlined" v-else />
          <div class="ant-upload-text" v-if="uploadTextDisplay">{{ uploadTextDisplay }}</div>
        </div>
      </template>
    </template>
    <template v-else>
      <slot></slot>
    </template>
    <template #[item]="data" v-for="item in componentSlots">
      <slot :name="item" v-bind="data || {}"></slot>
    </template>
    <a-image
      :style="{ display: 'none' }"
      :preview="{
        visible: state.visible,
        onVisibleChange: setVisible
      }"
      :src="state.previewImage"
    />
  </a-upload>
</template>

<script setup lang="ts">
import { propTypes } from "@/utils/propTypes";
import request from "@/utils/request";
import { buildUUID } from "@/utils/uuid";
import { cloneDeep, omit } from "lodash-es";
import { isString, isImageUrl, isBoolean } from "@/utils/is";
import type { UploadChangeParam, UploadProps } from "ant-design-vue";
import type { ShowType, UploadFile } from "./types";
import type { Slots } from "vue";
const { notification } = useMessage();
const emit = defineEmits(["update:fileUrl", "change", "update:fileList"]);
const { slots } = useSlots() as Slots;
const componentSlots = Object.keys(omit(slots, "default"));

const props = defineProps({
  fileUrl: propTypes.string.def(""),
  fileList: {
    type: Array as PropType<UploadFile[]>,
    default: () => []
  },
  action: {
    type: String,
    default: ""
  },
  listType: propTypes.string.def("picture"),
  multiple: propTypes.bool.def(true),
  uploadText: propTypes.string.def(""),
  showType: {
    type: String as PropType<ShowType>,
    default: "button"
  },
  beforeUpload: {
    type: Function as PropType<(file: File) => boolean | Promise<boolean>>
  }
});

const state = reactive({
  fileList: [] as UploadFile[],
  loading: false,
  avatarUrl: "" as string | undefined,
  isSetValue: true,
  isBlocked: false, // 标记是否要阻止文件上传
  previewImage: "",
  visible: false
});

const attrs = useAttrs();
const propsRef = reactive<Recordable>({});

const setVisible = (value: boolean): void => {
  state.visible = value;
};

const uploadUrl = computed(() => {
  if (props.action) {
    return props.action;
  }
  return isImageType.value ? "/upload/image" : "/upload/file";
});


const isImageType = computed(()=>{
  return ["avatar", "image"].includes(props.showType)
})

watch(
  () => state.avatarUrl,
  val => {
    if (props.showType != "avatar") return;
    if (val) {
      propsRef.openFileDialogOnClick = false;
    } else {
      propsRef.openFileDialogOnClick = true;
    }
  },
  {
    immediate: true
  }
);

const clearUploadFile = () => {
  state.avatarUrl = "";
  state.fileList = [];
  emit("update:fileList", []);
  emit("update:fileUrl", "");
};

watch(
  () => props.multiple,
  val => {
    if (val == false) {
      propsRef.multiple = false;
      propsRef.maxCount = 1;
    }
  },
  {
    immediate: true
  }
);

watch(
  () => props.showType,
  val => {
    if (["avatar", "image"].includes(val)) {
      propsRef.listType = "picture-card";
      propsRef.accept = 'image/*';
    }
    if (val == "avatar") {
      propsRef.showUploadList = false;
      propsRef.multiple = false;
      propsRef.maxCount = 1;
    }
  },
  {
    immediate: true
  }
);

watch(
  () => props.fileList,
  val => {
    state.fileList = val ? cloneDeep(val) : [];
  },
  {
    deep: true,
    immediate: true
  }
);

const uploadTextDisplay = computed(() => {
  const map: Recordable = {
    button: "上传",
    image: "上传图片"
  };
  return props.uploadText ? props.uploadText : map[props.showType] || "";
});

watch(
  () => props.fileUrl,
  val => {
    if (!val) {
      state.avatarUrl = "";
      return;
    }
    // 校验条件
    if (!isString(val) || !state.isSetValue || !unref(isImageType)) {
      return;
    }
    const urls = val.split(",").filter(Boolean); // 过滤空值
    const fileList = urls.map(url => ({
      url,
      uid: buildUUID(),
      name: url
    }));
    state.fileList = fileList;
    state.avatarUrl = fileList[0]?.url || "";
  },
  {
    deep: true,
    immediate: true
  }
);

const getProps = computed(() => {
  const propsData: Recordable = {
    ...attrs,
    ...props,
    ...unref(propsRef)
  };
  return omit(propsData, ["modelValue", "showType"]);
});

const avatarPreview = () => {
  state.previewImage = state.avatarUrl as string;
  setVisible(true);
};

const handlePreview = (file: UploadFile) => {
  if (!file.url) return;
  if (isImageUrl(file.url)) {
    state.previewImage = file.url;
    setVisible(true);
  } else {
    const link = document.createElement("a");
    link.style.display = "none";
    link.href = file.url;
    link.setAttribute("download", file.name || "file");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }
};


//检查文件类型
function isFileTypeAccepted(file: File, accept: string): boolean {
  const acceptedTypes = accept.split(",").map(t => t.trim().toLowerCase());
  const fileExtension = file.name.split('.').pop()?.toLowerCase() || '';
  const fileType = file.type.toLowerCase();

  return acceptedTypes.some(type => {
    // 处理类似 image/* 的通配符
    if (type.includes('/*')) {
      const mainType = type.split('/*')[0];
      return fileType.startsWith(mainType);
    }

    // 处理具体类型如 .png, image/png
    return (
      type === `.${fileExtension}` ||
      type === fileType ||
      new RegExp(`^${type.replace(".", "\\.").replace("*", ".*")}$`, "i").test(fileType)
    );
  });
}


const handleBeforeUpload: UploadProps["beforeUpload"] = async file => {
  const { accept, beforeUpload } = unref(getProps);
  state.isBlocked = false;
  // 如果设置了accept属性，检查文件类型是否匹配
  if (accept && !isFileTypeAccepted(file, accept)) {
    notification.error({
      class: "notice-danger",
      message: `${file.name} 上传失败！`,
      description: `文件类型不匹配，请选择正确的文件类型(${accept})`,
      duration: 2
    });
    state.isBlocked = true;
    return false;
  }

  if (beforeUpload) {
    const result = await beforeUpload(file);
    if (isBoolean(result) && result === false) {
      state.isBlocked = true;
    }
    return result;
  }

  return true;
};

//处理上传失败
const errorHandler = (info: UploadChangeParam) => {
  const { response } = info.file.error;
  const description = response?.data?.msg || response?.data?.message;
  notification.open({
    class: "notice-danger",
    message: `${info.file.name} 上传失败！`,
    description: description,
    duration: 2
  });
};

//文件url
const fileUrl = computed(() => {
  const fileUrls = [];
  for (const file of state.fileList) {
    if (file.status === "error") continue;
    fileUrls.push(file.url);
  }
  return fileUrls.join(",");
});

//获取上传的文件
const getUploadFileList = (fileList: UploadFile[]) => {
  return fileList.map(file => {
    if (file.response && file.response.data) {
      file.url = file.response.data.url;
      file.id = file.response.data.id;
    }
    file.percent = file.percent && Number(file.percent);
    return file;
  });
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
}: any) => {
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

const handleChange = (info: UploadChangeParam) => {
  if (state.isBlocked) {
    state.isBlocked = false;
    return;
  }
  const { status } = info.file;
  if (status === "uploading") {
    state.loading = true;
  }
  const fileList = getUploadFileList(info.fileList);
  state.fileList = fileList;
  state.avatarUrl = state.fileList[0]?.url;
  if (status === "done" || status === "removed") {
    state.loading = false;
    state.isSetValue = false;
    emit("update:fileUrl", unref(fileUrl));
    emit("update:fileList", state.fileList);
  }
  if (status === "error") {
    state.loading = false;
    errorHandler(info);
  }
  emit("change", info);
};

defineExpose({
  clearUploadFile
});
</script>
<style lang="less" scoped>
.upload-image,
.upload-avatar {
  :deep(.ant-upload-list .ant-upload-list-item) {
    width: 90px;
    height: 90px !important;
  }

  :deep(.ant-upload.ant-upload-select-picture-card) {
    width: 90px;
    height: 90px !important;
  }
}

.ant-form-item-has-error {
  .upload-image {
    :deep(.ant-upload-select) {
      border: 1px solid red;
    }
  }
}

.upload-avatar {
  .avatar-url {
    width: 90px;
    height: 90px;
  }

  .ant-upload-preview {
    position: relative;
    margin: 0 auto;
    width: 100%;
    border-radius: 6px;
    box-shadow: 0 0 4px #ccc;

    .upload-icon {
      width: 25px;
      height: 25px;
      font-size: 16px;
      padding: 0.3rem;
      background: rgba(222, 221, 221, 0.7);
      border-radius: 50%;
      border: 1px solid rgba(0, 0, 0, 0.2);
    }

    .mask {
      opacity: 0;
      position: absolute;
      background: rgba(0, 0, 0, 0.4);
      cursor: pointer;
      transition: opacity 0.4s;
      display: flex;
      justify-content: center;
      align-items: center;

      &:hover {
        opacity: 1;
      }
    }

    img,
    .mask {
      width: 100%;
      height: 90px;
      border-radius: 6px;
      overflow: hidden;
    }
  }
}
</style>

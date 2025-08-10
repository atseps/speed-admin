<template>
  <div class="wang-editor" :style="{ width: getWidth }">
    <Toolbar class="wang-toolbar" :editor="editorRef" :defaultConfig="toolbarConfig" :mode="mode" />
    <Editor
      :defaultConfig="editorConfig"
      :mode="mode"
      v-model="value"
      :style="{ height: getHeight,'overflow-y':'hidden' }"
      @onCreated="handleCreated"
    />
  </div>
</template>

<script setup lang="ts">
import "@wangeditor/editor/dist/css/style.css";
import { Editor, Toolbar } from "@wangeditor/editor-for-vue";
import { Form } from "ant-design-vue";
import type { IDomEditor } from "@wangeditor/editor";
const formItemContext = Form.useInjectFormItemContext();
type InsertFnType = (url: string, alt: string, href: string) => void;
const props = defineProps({
  modelValue: {
    type: String,
    required: true
  },
  height: {
    type: [Number,String],
    default: 400
  },
  width: {
    type: [Number,String],
    default: '100%'
  }
});

const getWidth = computed(() => {
  return typeof props.width === 'number' ? `${props.width}px` : props.width;
});

const getHeight = computed(() => {
  return typeof props.height === 'number' ? `${props.height}px` : props.height;
});

const emit = defineEmits(["update:modelValue", "uploadImgFromMedia"]);

const value = computed({
  get() {
    return props.modelValue;
  },
  set(value) {
    nextTick(() => {
      emit("update:modelValue", unref(editorRef).isEmpty() ? "" : value);
      formItemContext.onFieldChange();
    });
  }
});

// 编辑器实例，必须用 shallowRef，重要！
const editorRef = shallowRef();

const mode = ref("default");

const toolbarConfig = {
  excludeKeys: [
    "group-more-style", // 排除菜单组，写菜单组 key 的值即可
    "group-image",
    "group-video"
  ],
  insertKeys: {
    index: 20,
    keys: "uploadImage"
  }
};

const editorConfig = {
  autoFocus: false,
  MENU_CONF: {
    uploadImage: {
      customBrowseAndUpload(insertFn: InsertFnType) {
        // TS 语法
        emit("uploadImgFromMedia", insertFn);
      }
    }
  },
  placeholder: "请输入内容..."
};

// 组件销毁时，也及时销毁编辑器
onBeforeUnmount(() => {
  const editor = editorRef.value;
  if (editor == null) return;
  editor.destroy();
});

// 编辑器回调函数
const handleCreated = (editor: IDomEditor) => {
  editorRef.value = editor; // 记录 editor 实例！
};
</script>
<style lang="less" scoped>
.wang-editor {
  border: 1px solid var(--w-e-textarea-border-color);
  margin-top: 10px;
  z-index: 99;
  border-radius: var(--ant-border-radius);
}
:deep(.w-e-toolbar),
:deep(.w-e-text-container) {
  border-radius: var(--ant-border-radius);
}
.wang-toolbar {
  border-bottom: 1px solid var(--w-e-textarea-border-color);
}

.ant-form-item-has-error {
  .wang-editor {
    border: 1px solid red;
  }
}
</style>

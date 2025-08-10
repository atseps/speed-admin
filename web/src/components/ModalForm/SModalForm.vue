<template>
  <s-modal
    @register="register"
    v-bind="$attrs"
    :destroyOnClose="destroyOnClose"
    :afterClose="onCancel"
    :title="getTitle"
    :width="width"
    @ok="onSubmit"
  >
    <a-form v-bind="getFormProps" ref="formRef" :labelCol="getLabelCol">
      <a-card :body-style="bodyStyleRef" :bordered="false">
        <slot></slot>
      </a-card>
    </a-form>
  </s-modal>
</template>
<script lang="ts" setup>
import { SModal, useModalInner } from "@/components/Modal";
import { formProps } from "ant-design-vue/lib/form";
import { isFunction, isBoolean, isString, isNumber, isObject } from "@/utils/is";
import { keys, pick, omit, cloneDeep, difference } from "lodash-es";
type Key = string | number;
const { responseNotice, message } = useMessage();
const emit = defineEmits([
  "cancel",
  "finishFailed",
  "ok",
  "finish",
  "register",
  "loadSuccess",
  "saveSuccess",
  "setFormValue"
]);

type LayoutGrid = {
  col: number;
  gutter?: number;
};

const props = defineProps(
  Object.assign({}, formProps(), {
    saveApi: { type: Function as PropType<Fn> },
    readApi: { type: Function as PropType<Fn> },
    showLoading: {
      type: Boolean,
      default: true
    },
    beforeSubmit: Function as PropType<Fn>,
    model: {
      type: Object,
      required: true
    },
    destroyOnClose: {
      type: Boolean,
      default: true
    },
    onOk: Function as PropType<Fn>,
    width: {
      type: [Number, String],
      default: "50%"
    },
    title: {
      type: String,
      default: ""
    },
    //自定义标题
    modalTitle: {
      type: String,
      default: ""
    },
    labelWidth: {
      type: String,
      default: "80px"
    },
    layout: {
      type: String,
      default: "horizontal"
    },
    bodyStyle: {
      type: Object,
      default: () => {
        return {};
      }
    },
    dataKey: {
      type: String,
      default: "id"
    },
    returnFormDataOnSave: {
      type: Boolean
    },
    //栅格布局配置
    layoutGrid: {
      type: Object as PropType<LayoutGrid>
    }
  })
);

const initModel = ref();
const record = ref();
const formRef = ref();

onMounted(() => {
  initModel.value = cloneDeep(props.model);
});

const bodyStyleRef = computed(() => {
  const { layoutGrid, bodyStyle } = props;
  const style = {
    ...bodyStyle,
    display: layoutGrid && "grid",
    gridTemplateColumns: layoutGrid?.col && `repeat(${layoutGrid?.col}, 1fr)`,
    gridColumnGap: layoutGrid?.gutter && `${layoutGrid?.gutter}px`
  };
  return style;
});

const getLabelCol = computed(() => {
  const { layout, labelCol } = props;
  if (labelCol) return labelCol;
  if (layout == "horizontal") {
    return { flex: props.labelWidth };
  } else {
    return {};
  }
});

const getFormProps = computed(() => {
  const exclude = difference(keys(props), keys(formProps()));
  return { ...omit(props, exclude) };
});

const getTitle = computed(() => {
  const { title, readApi, dataKey, modalTitle } = props;
  if (modalTitle) return modalTitle;
  let headTitle = `添加${title}`;
  if (readApi && unref(record) && !isNaN(record.value)) {
    headTitle = `修改${title}`;
  } else if (unref(record) && unref(record)[dataKey]) {
    headTitle = `修改${title}`;
  }
  return headTitle;
});

const [register, { changeLoading, closeModal, changeOkLoading }] = useModalInner(
  (data: Recordable | Key) => {
    if (!data) return;
    record.value = data;
    if (props.readApi && !isObject(record.value)) {
      if (isString(record.value) || isNumber(record.value)) {
        getFormData(data as Key);
      }
    } else {
      const formData = cloneDeep(data) as Recordable;
      setFormValue(formData);
    }
  }
);

const setFormValue = (data: Recordable) => {
  const formModel = pick(data, keys(props.model));
  Object.assign(props.model, formModel);
  emit("setFormValue", formModel);
};

const getFormData = async (id: Key) => {
  const { readApi, showLoading } = props;
  if (id && readApi && isFunction(readApi)) {
    showLoading && changeLoading(true);
    try {
      const res = await readApi(id);
      if (res.code == 1) {
        setFormValue(res.data);
        emit("loadSuccess", res.data);
      } else {
        message.error(res.msg);
      }
    } finally {
      changeLoading(false);
    }
  }
};

const onCancel = async () => {
  await nextTick();
  formRef.value?.clearValidate();
  setFormValue(unref(initModel));
  record.value = null;
  emit("cancel");
};

//表单提交
const onSubmit = async () => {
  //自定义提交逻辑
  if (props.onOk && isFunction(props.onOk)) {
    emit("ok", formRef.value);
    return;
  }
  const { saveApi, beforeSubmit } = props;
  const formData = cloneDeep(props.model);
  try {
    const values = await formRef.value.validate();
    emit("finish", values);
    if (!saveApi && !isFunction(saveApi)) return;
    //提交表单之前的处理函数
    if (beforeSubmit && isFunction(beforeSubmit)) {
      const res = await beforeSubmit(formData);
      if (isBoolean(res) && !res) return;
    }
    try {
      changeOkLoading(true);
      const res = await saveApi(formData);
      if (res.code == 1) {
        const params = props.returnFormDataOnSave ? formData : {};
        emit("saveSuccess", params);
        closeModal();
      }
      responseNotice(res);
    } finally {
      changeOkLoading(false);
    }
  } catch (error) {
    emit("finishFailed", error);
  }
};

const getFormRef = () => {
  return unref(formRef);
};

defineExpose({
  getFormRef,
  closeModal,
  onSubmit,
  changeOkLoading
});
</script>

<style lang="less" scoped>
:deep(.ant-card-body) {
  &:after,
  &:before {
    display: none;
  }
}

:deep(.ant-card) {
  box-shadow: none;
}
</style>

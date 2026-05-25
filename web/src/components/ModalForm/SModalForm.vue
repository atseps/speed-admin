<template>
  <s-modal
    @register="register"
    v-bind="$attrs"
    :destroyOnClose="destroyOnClose"
    :afterClose="onCancel"
    :title="getTitle"
    :width="width"
    @ok="onSubmit"
    @visible-change="onVisibleChange"
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
  "loadSuccess",
  "saveSuccess",
  "setFormValue",
  "register",
  "visible-change"
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
    },
    beforeSetFormValue: {
      type: Function as PropType<(data: Recordable) => Recordable>,
      default: null
    }
  })
);

const initModel = ref();
const record = ref();
const formRef = ref();

onMounted(() => {
  initModel.value = cloneDeep(props.model);
});


const onVisibleChange = async (visible: boolean) => {
  if (visible) {
    await nextTick();
  }
  emit("visible-change", visible, unref(isEdit));
};

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

const isValidId = (val: unknown): boolean => {
  const num = typeof val === 'number' ? val : Number(val);
  return Number.isFinite(num) && num > 0;
};

const isEdit = computed(() => {
  const rec = unref(record);
  return isValidId(rec) || (isObject(rec) ? isValidId((rec)[props.dataKey]): false);
});

const getTitle = computed(() => {
  if (props.modalTitle) return props.modalTitle;
  return isEdit.value ? `修改${props.title}` : `添加${props.title}`;
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

const beforeSetFormValueFn = (data: Recordable): Recordable => {
  let formData = cloneDeep(data);
  if (props.beforeSetFormValue && isFunction(props.beforeSetFormValue)) {
    const result = props.beforeSetFormValue(formData);
    if (isObject(result)) {
      formData = result;
    }
  }
  return formData;
};

const setFormValue = (data: Recordable) => {
  const processedData = beforeSetFormValueFn(data);
  const formModel = pick(processedData, keys(props.model));
  Object.assign(props.model, formModel);
  emit("setFormValue", formModel);
};

const resetFormData = () => {
  const formModel = pick(unref(initModel), keys(props.model));
  Object.assign(props.model, formModel);
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
  resetFormData();
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
  let formData = cloneDeep(props.model);
  try {
    const values = await formRef.value.validate();
    emit("finish", values);
    if (!saveApi && !isFunction(saveApi)) return;
    //提交表单之前的处理函数
    if (beforeSubmit && isFunction(beforeSubmit)) {
      const result = await beforeSubmit(formData);
      if (isBoolean(result) && !result) {
        return;
      }else if(isObject(result)){
        formData = result;
      }
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
    console.error(error)
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
  changeOkLoading,
  changeLoading,
  setFormValue,
  resetFormData
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

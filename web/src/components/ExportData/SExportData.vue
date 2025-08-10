<template>
  <div class="spe-export-data">
    <s-button icon="export-outlined" @click="onClick">
      {{ text }}
    </s-button>
    <s-modal
      :minHeight="100"
      destroyOnClose
      :title="title"
      @register="register"
      :width="500"
      @ok="handleSubmit"
      @cancel="onCancel"
    >
      <a-form style="padding: 30px" :model="model">
        <s-compact-select
          :options="options"
          :model="model"
          :presets="presets"
          type="date"
          :defaultField="defaultField"
        ></s-compact-select>
      </a-form>
    </s-modal>
  </div>
</template>

<script setup lang="ts">
import { useModal } from "@/components/Modal";
import { isFunction, isBoolean, isEmpty } from "@/utils/is";
import { useLoading } from "@/components/Loading";
import { download } from "@/utils";
import type { OptionType, PresetType } from "@/components/CompactSelect";
import dayjs from "dayjs";

interface ExportModel {
  [key: string]: [dayjs.Dayjs, dayjs.Dayjs] | undefined;
}

const { message } = useMessage();
const props = defineProps({
  api: {
    type: Function,
    required: true
  },
  queryParams: {
    type: Object,
    default: () => {}
  },
  options: {
    type: Array as PropType<OptionType[]>,
    default: () => []
  },
  beforeExport: {
    type: Function
  },
  text: {
    type: String,
    default: "导出"
  },
  title: {
    type: String,
    default: "导出范围"
  },
  maxExportDays: {
    type: Number
  }
});

const [openFullLoading, closeFullLoading] = useLoading({
  tip: "导出中..."
});

const model = ref<ExportModel>({});

const defaultField = ref("");

const [register, { openModal, closeModal }] = useModal();

const presets: PresetType[] = [
  {
    label: "今日",
    value: [dayjs(), dayjs()]
  },
  {
    label: "本周",
    value: [dayjs().startOf("week").add(1, "day"), dayjs().endOf("week").add(1, "day")]
  },
  {
    label: "本月",
    value: [dayjs().startOf("month"), dayjs().endOf("month")]
  }
];

/**
 * 计算时间相差天数
 */
function diffDate(date: Array<any>, days = 31) {
  let [startDate, endDate] = date;
  startDate = Number(startDate);
  endDate = Number(endDate);
  const between = dayjs(endDate).diff(dayjs(startDate), "day");
  if (between > days) {
    return false;
  }
  return true;
}

const handleSubmit = () => {
  const { maxExportDays } = props;
  let fieldValue: [dayjs.Dayjs, dayjs.Dayjs] = [] as unknown as [dayjs.Dayjs, dayjs.Dayjs];
  if (!isEmpty(model.value)) {
    Object.keys(model.value).forEach(key => {
      const value = model.value[key];
      if (key && value) {
        fieldValue = value;
      }
    });
    if (isEmpty(fieldValue)) {
      message.error("请选择时间进行导出", 1);
      return;
    }
    if (maxExportDays && maxExportDays > 0) {
      const result = diffDate(fieldValue, maxExportDays);
      if (!result) {
        message.error(`导出时间不能大于${maxExportDays}天`, 1);
        return;
      }
    }
    exportData(model.value);
    closeModal();
    onCancel();
  } else {
    message.error("请选择时间进行导出", 1);
  }
};

const onCancel = () => {
  model.value = {};
};

const onClick = () => {
  const { options } = props;
  if (options && options?.length > 0) {
    defaultField.value = options[0].value as string;
    openModal();
  } else {
    exportData();
  }
};

const exportData = async (queryParam = {}) => {
  const { api, beforeExport } = props;
  let params = Object.assign({}, unref(props.queryParams), queryParam);
  //导出之前的处理函数
  if (beforeExport && isFunction(beforeExport)) {
    params = await beforeExport(params);
    if (isBoolean(params) && !params) {
      return;
    }
  }
  //执行请求
  openFullLoading();
  try {
    const res = await api(params);
    closeFullLoading();
    download(res);
  } catch {
    closeFullLoading();
  }
};
</script>

<style lang="less" scoped>
.spe-export-data {
  display: inline-block;
}
</style>

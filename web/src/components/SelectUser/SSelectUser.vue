<template>
  <a-form-item-rest v-bind="$attrs">
    <a-input v-loading="loading" v-bind="props" :value="valueText" readonly @click="openModal">
      <template #suffix>
        <span v-if="valueText" @click.stop="handleClear" :style="iconStyle">
          <s-icon type="close-circle-filled"></s-icon>
        </span>
        <s-icon :style="iconStyle" type="down-outlined"></s-icon>
      </template>
    </a-input>

    <s-select-user-modal
      :multiple="multiple"
      @register="register"
      :selectedRows="selectedRows"
      @submit="onSelectUser"
    />
  </a-form-item-rest>
</template>

<script setup lang="ts">
import inputProps from "ant-design-vue/lib/input/inputProps";
import { getUserById } from "@/api/system/user";
import { useModal } from "@/components/Modal";
import SSelectUserModal from "./SSelectUserModal.vue";
import { InputProps } from "ant-design-vue";
import { UserRecord } from "./SSelectUserModal.vue";
import { Form } from "ant-design-vue";
const formItemContext = Form.useInjectFormItemContext();
const emit = defineEmits(["update:modelValue", "register"]);
const [register, { openModal }] = useModal();

const props = defineProps({
  ...inputProps(),
  multiple: {
    type: Boolean,
    default: false
  },
  showLoading: {
    type: Boolean,
    default: true
  },
  modelValue: {
    type: [String,Number]
  }
});

const valueText = ref("");
const loading = ref(false);
const isFetch = ref(true);
const selectedRows = ref<UserRecord[]>([]);

const iconStyle = computed(() => {
  return {
    fontSize: "12px",
    color: "rgba(0, 0, 0, 0.25)",
    cursor: "pointer"
  };
});

function clearData() {
  valueText.value = "";
  selectedRows.value = [];
}

const handleClear = () => {
  clearData();
  emit("update:modelValue", "");
  formItemContext.onFieldChange();
};

watch(
  () => props.modelValue,
  async (value: InputProps["value"]) => {
    if (!value) return clearData();
    if (!isFetch.value) return;
    try {
      props.showLoading && (loading.value = true);
      const { data }: { data: UserRecord[] } = await getUserById(value);
      valueText.value = data.map(item => item.realname).join();
      selectedRows.value = data;
    } finally {
      loading.value = false;
    }
  },
  {
    immediate: true
  }
);

const onSelectUser = (rows: UserRecord[]) => {
  selectedRows.value = rows;
  const realname = rows.map(item => item.realname);
  valueText.value = realname.join();
  const value = rows.map(item => item.id)?.join();
  emit("update:modelValue", value);
  formItemContext.onFieldChange();
  isFetch.value = false;
};
</script>

<style lang="css" scoped>
:deep(.ant-input) {
  cursor: pointer;
}
</style>

<template>
  <s-modal-form v-bind="getBindValue">
    <s-input label="字典名称" name="name" v-model="form.name" placeholder="请输入字典名称" />

    <s-input
      label="字典值"
      name="value"
      v-model="form.value"
      placeholder="请输入字典值"
    />

    <s-select
      v-if="isDictType"
      label="组件类型"
      name="widget_type"
      :options="options"
      v-model="form.widget_type"
      :rules="[{ required: true, message: '请选择组件类型' }]"
      placeholder="请选择组件类型"
    />

    <a-form-item label="颜色" v-if="!isDictType">
      <a-input-group compact class="mb-[12px]">
        <a-input style="width: 85%" v-model:value="form.color" placeholder="请输入颜色" />
        <a-form-item-rest>
          <a-input style="width: 15%" type="color" v-model:value="form.color" />
        </a-form-item-rest>
      </a-input-group>
    </a-form-item>

    <s-textarea
      label="备注"
      name="note"
      v-model="form.note"
      placeholder="请输入备注"
      :auto-size="{ minRows: 2 }"
    />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save } from "@/api/system/dict";

const props = defineProps({
  category: [Number, String]
});

const isDictType = computed(() => {
  return form.type == "dict_type";
});

const form = reactive({
  id: undefined,
  widget_type: "text",
  type: "",
  name: "",
  value: "",
  note: "",
  color: ""
});

const options = ref([
  {
    label: "标签",
    value: "tag"
  },
  {
    label: "徽标数",
    value: "badge"
  },
  {
    label: "文本",
    value: "text"
  }
]);

const beforeSubmit = (data: Recordable) => {
  data.category = props.category;
};

const rules = computed(() => {
  return {
    typeof: [{ required: true, message: "请选择字典类型" }],
    name: [{ required: true, message: "请输入字典名称" }],
    value: [{ required: true, message: "请输入属性值" }]
  };
});

const getBindValue = computed(() => {
  return {
    width: 580,
    title: "字典",
    model: form,
    saveApi: save,
    returnFormDataOnSave:true,
    bodyStyle: { padding: "20px" },
    rules: unref(rules),
    beforeSubmit: beforeSubmit
  };
});
</script>

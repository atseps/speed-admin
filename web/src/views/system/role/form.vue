<template>
  <s-modal-form v-bind="getBindValue">
    <s-input label="角色名称" name="name" v-model="form.name" placeholder="请输入角色名称" />
    <s-input
      label="权限标识"
      name="role_key"
      v-model="form.role_key"
      placeholder="请输入权限标识"
    />
    <s-select
      label="数据范围"
      name="data_range"
      v-model="form.data_range"
      placeholder="请选择数据范围"
      :options="options"
    />
    <a-form-item label="部门团队" name="departments" v-if="form.data_range == 2">
      <s-select-dept
        placeholder="请选择部门"
        :treeData="treeData"
        v-model:value="form.departments"
        treeCheckable
      />
    </a-form-item>
    <s-textarea label="备注" v-model="form.note" placeholder="请输入备注" />
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/system/role";
import { getDeptList } from "@/api/system/department";
const form = reactive({
  id: undefined,
  data_range: undefined,
  departments: undefined,
  name: "",
  role_key: "",
  note: ""
});

const rules = reactive({
  name: [{ required: true, message: "请输入角色名称" }],
  role_key: [{ required: true, message: "请输入权限标识" }],
  data_range: [{ required: true, message: "请选择数据范围" }],
  departments: [{ required: true, message: "请选择部门" }]
});

const options = ref([
  { value: 1, label: "全部" },
  { value: 2, label: "自定义数据" },
  { value: 3, label: "本人数据" },
  { value: 4, label: "部门数据" },
  { value: 5, label: "部门及以下数据" }
]);

const beforeSubmit = (data: Recordable) => {
  if (data.data_range != 2) {
    data.departments = [];
  }
};

onMounted(() => {
  getTreeData();
});

const treeData =  ref([])
const getTreeData = async () => {
  const res = await getDeptList();
  treeData.value = res.data;
};


const getBindValue = computed(() => {
  return {
    width: 540,
    title: "角色",
    model: form,
    saveApi: save,
    readApi: getEdit,
    beforeSubmit: beforeSubmit,
    rules: rules
  };
});
</script>

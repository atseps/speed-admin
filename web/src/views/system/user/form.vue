<template>
  <s-modal-form v-bind="getBindValue">
    <s-input
      :readonly="!!form.id"
      label="用户名"
      name="username"
      v-model="form.username"
      placeholder="请输入用户名"
    />
    <s-input label="姓名" name="realname" v-model="form.realname" placeholder="请输入姓名" />

    <s-input label="手机号码" name="phone" v-model="form.phone" placeholder="请输入手机号码" />
    <s-input label="邮箱" name="email" v-model="form.email" placeholder="请输入邮箱" />

    <s-select
      class="col-span-2"
      label="角色"
      name="roles"
      mode="multiple"
      v-model="form.roles"
      :options="role"
      labelField="name"
      valueField="id"
      placeholder="请选择角色"
      allow-clear
    />

    <s-tree-select
      class="col-span-2"
      labelField="name"
      label="部门"
      name="dept_id"
      :treeData="department"
      allow-clear
      placeholder="请选择部门"
      v-model="form.dept_id"
    />

    <a-form-item label="头像" name="avatar">
      <s-upload show-type="avatar" v-model:fileUrl="form.avatar"></s-upload>
    </a-form-item>

    <div class="ml-[70px] col-span-2" style="color: rgb(255, 85, 0)">初始密码：123456</div>
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save, getEdit } from "@/api/system/user";
type Option = {
  name: string;
  id: string;
};

type DepartmentOpt = {
  title: string;
  value: string;
  children: DepartmentOpt[];
};

const form = reactive({
  id: undefined,
  dept_id: undefined,
  username: "",
  phone: "",
  email: "",
  roles: [],
  realname: "",
  avatar: ""
});

defineProps({
  role: {
    type: Array as PropType<Option[]>
  },
  department: {
    type: Array as PropType<DepartmentOpt[]>
  }
});

const rules = reactive({
  username: [{ required: true, message: "请输入用户名" }],
  roles: [{ required: true, message: "请选择角色" }],
  dept_id: [{ required: true, message: "请选择部门" }],
  realname: [{ required: true, message: "请输入姓名" }]
});

const getBindValue = computed(() => {
  return {
    width: 660,
    title: "用户",
    model: form,
    saveApi: save,
    labelWidth: "70px",
    readApi: getEdit,
    layoutGrid: { col: 2 },
    bodyStyle: { padding: "12px" },
    rules: rules
  };
});
</script>

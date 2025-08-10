<template>
  <page-view autoHeight>
    <a-row :gutter="8" class="h-full">
      <a-col :span="4" class="h-full overflow-hidden" style="border-radius: calc(var(--ant-border-radius) * 2);">
        <s-tree
          :loading="loading"
          @select="handleSelect"
          :field-names="{ key: 'id' }"
          v-model:selectedKeys="selectedKeys"
          search
          :treeData="state.department"
        />
      </a-col>
      <a-col :span="20">
        <s-table @register="register" @reset="resetSearch">
          <template #toolbar>
            <s-button type="primary" icon="plus-outlined" @click="openModal(true, {})"
              >添加</s-button
            >
          </template>
          <template #bodyCell="{ value, column, record }">
            <template v-if="column.dataIndex === 'action' && record.is_admin != 1">
              <a v-auth="'system:user:update'" @click="openModal(true, record.id)"><s-icon type="edit-outlined" />修改</a>
              <a-divider type="vertical" />
              <a v-auth="'system:user:resetPassword'" @click="onResetPassword(record.id)"><s-icon type="sync-outlined" /> 重置密码</a>
            </template>

            <template v-if="column.dataIndex === 'status'">
              <a-switch
                :disabled="record.is_admin == 1"
                :checked="record.status == 1"
                checked-children="激活"
                un-checked-children="禁用"
                @change="onChangeStatus($event, record)"
              />
            </template>

            <template v-if="column.dataIndex === 'roles'">
              <a-tag v-for="(item, index) in value" :key="index" color="blue">{{
                item.name
              }}</a-tag>
            </template>
          </template>
        </s-table>
      </a-col>
    </a-row>
    <user-form
      @register="registerModal"
      @save-success="search"
      :role="state.role"
      :department="state.department"
    />
    <reset-pwd @register="registerModalPwd" />
  </page-view>
</template>

<script setup lang="ts">
import { useTable } from "@/components/Table";
import { useModal } from "@/components/Modal";
import ResetPwd from "./ResetPwd.vue";
import { getUserList, changeStatus, resetPassword } from "@/api/system/user";
import { getRoleAll } from "@/api/system/role";
import { getDeptList } from "@/api/system/department";
import userForm from "./form.vue";
import { useLoading as useFullLoading } from "@/components/Loading";
type CheckedType = boolean | string | number;
const { message, createConfirm } = useMessage();
const { loading, setLoading } = useLoading();

const columns = [
  {
    title: "用户名",
    dataIndex: "username",
    props: { type: "link" }
  },
  {
    title: "姓名",
    dataIndex: "realname"
  },
  {
    title: "部门",
    dataIndex: "department_name"
  },
  {
    title: "角色",
    dataIndex: "roles"
  },
  {
    title: "手机号码",
    dataIndex: "phone",
    width: 110
  },
  {
    title: "状态",
    dataIndex: "status"
  },
  {
    title: "添加时间",
    dataIndex: "create_time"
  },
  {
    title: "操作",
    dataIndex: "action",
    fixed: "right"
  }
];

const state = reactive({
  queryParams: {},
  role: [],
  department: []
});

const searchForm = ref([
  {
    title: "用户名称",
    dataIndex: "key",
    props: {
      placeholder: "请输入姓名、拼音搜索"
    }
  },
  {
    title: "角色",
    dataIndex: "roles",
    component: "Select",
    props: {
      allowClear: true,
      placeholder: "请选择角色",
      fieldNames: { label: "name", value: "id" },
      options: toRef(state, "role")
    }
  },
  {
    title: "用户状态",
    dataIndex: "status",
    component: "Select",
    props: {
      placeholder: "请选择状态",
      options: [
        { value: 0, label: "禁用" },
        { value: 1, label: "激活" }
      ]
    }
  },
  {
    title: "添加时间",
    dataIndex: "create_time",
    component: "RangePicker"
  }
]);

const selectedKeys = ref([]);

const [register, { search, getFormValue }] = useTable({
  columns,
  searchForm,
  listApi: getUserList,
  beforeFetch: (params: Recordable) => {
    params.dept_id = unref(selectedKeys)[0];
  },
  debounceRender: false,
  canResize: true
});

const [registerModal, { openModal }] = useModal();

const [registerModalPwd, { openModal: openModalPwd }] = useModal();

//加载初始数据
const loadIntData = () => {
  getRoleAll().then(({ data }) => {
    state.role = data;
  });
  setLoading(true);
  getDeptList()
    .then(({ data }) => {
      state.department = data;
    })
    .finally(() => {
      setLoading(false);
    });
};

//修改状态
const onChangeStatus = (checked: CheckedType, record: Recordable) => {
  record.status = checked ? 1 : 2;
  changeStatus(record.id).then(async res => {
    if (res.code == 1) {
      await message.success(res.msg, 1)
      search()
    } else {
      await message.error(res.msg, 1);
      search()
    }
  });
};

const [openFullLoading, closeFullLoading] = useFullLoading({
  tip: "密码重置中..."
});

//重置密码
const onResetPassword = (id: string | number) => {
  createConfirm({
    title: "确定要重置密码吗?",
    onOk: () => {
      openFullLoading();
      resetPassword(id).then((res) => {
        if(res.code == 1){
          openModalPwd(true, res.data);
        }else{
          message.error(res.msg, 1);
        }
      }).finally(()=>{
        closeFullLoading();
      })
    }
  });
};

//选中部门
const handleSelect = () => {
  const params = getFormValue();
  search(params);
};

const resetSearch = () => {
  selectedKeys.value = [];
};

onMounted(() => {
  loadIntData();
});
</script>

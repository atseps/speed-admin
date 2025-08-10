<template>
  <s-modal
    title="选择人员"
    @register="registerModal"
    destroyOnClose
    :width="800"
    :useContainer="false"
    @ok="handleSubmit"
    @cancel="cleanData"
  >
    <div style="height: 460px;">
      <a-form style="padding: 10px 20px" layout="inline" :model="queryParams" @finish="search">
        <a-form-item label="关键字">
          <a-input v-model:value="queryParams.key" placeholder="请输入姓名、拼音搜索" allow-clear />
        </a-form-item>
        <a-form-item>
          <s-button type="primary" icon="SearchOutlined" html-type="submit"> </s-button>
          <s-button class="ml-[10px]" icon="ReloadOutlined" @click="resetSearch"> </s-button>
        </a-form-item>
      </a-form>
      <a-row class="h-full overflow-hidden">
        <a-col :sm="4" :md="5">
          <s-tree
            blockNode
            class="border-top"
            default-expand-all
            :height="600"
            :loading="loading"
            @select="handleSelect"
            :field-names="{
              key: 'id'
            }"
            v-model:selectedKeys="selectedKeys"
            :treeData="state.department"
          />
        </a-col>
        <a-col class="border-left" :sm="20" :md="19">
          <s-table
            class="border-top"
            @register="register"
            :rowSelection="{
              selectedRowKeys: state.selectedRowKeys,
              hideSelectAll: true,
              type: multiple ? 'checkbox' : 'radio',
              onChange: onSelectChange,
              onSelect: onSelect
            }"
          />
        </a-col>
      </a-row>
    </div>
  </s-modal>
</template>

<script setup lang="tsx">
import { useTable } from "@/components/Table";
import { useModalInner } from "@/components/Modal";
import { getActiveUsers } from "@/api/system/user";
import { getDeptList } from "@/api/system/department";
import type { TreeDataItem } from "ant-design-vue/es/tree";
import type { Key } from "ant-design-vue/es/table/interface";
import type { TableColumnProps } from "@/components/Table/types";
export interface UserRecord {
  id: string | number;
  realname: string;
  department_name?: string;
  avatar?: string;
}

const { message } = useMessage();
const [registerModal, { closeModal }] = useModalInner();
const props = defineProps({
  listApi: {
    type: Function,
    default: getActiveUsers
  },
  deptApi: {
    type: Function,
    default: getDeptList
  },
  multiple: {
    type: Boolean,
    default: false
  },
  selectedRows: {
    type: Array as PropType<UserRecord[]>,
    default: () => []
  }
});

const queryParams: Recordable = reactive({
  key: undefined
});

const { loading, setLoading } = useLoading();

const columns: TableColumnProps[] = [
  {
    title: "头像",
    dataIndex: "avatar",
    width: 80,
    customRender: ({ value, record }) => {
      return (
        <a-avatar src={value ? value : ""} style={{ backgroundColor: value ? "" : "#1890ff" }}>
          {record.realname}
        </a-avatar>
      );
    }
  },
  {
    title: "姓名",
    dataIndex: "realname"
  },
  {
    title: "部门",
    dataIndex: "department_name"
  }
];

const state = reactive({
  department: [] as TreeDataItem[],
  selectedRowKeys: [] as Key[],
  selectedRows: [] as UserRecord[]
});

const customRow = (record: UserRecord) => {
  return {
    onClick: (event: MouseEvent) => {
      // 阻止复选框点击触发行点击
      const target = event.target as HTMLElement;
      if (target.closest('.ant-checkbox-wrapper, .ant-checkbox')) {
        return;
      }
      if (props.multiple) {
        let selected = true;
        if (state.selectedRowKeys.includes(record.id)) {
          selected = false;
        }
        onSelect(record, selected);
      } else {
        onSelectChange([record.id], [record]);
      }
    }
  };
};

//多选
const onSelect = (record: UserRecord, selected: boolean) => {
  if (!props.multiple) return;
  if (selected && !state.selectedRowKeys.includes(record.id)) {
    state.selectedRowKeys.push(record.id);
    state.selectedRows.push(record);
  } else {
    state.selectedRowKeys = state.selectedRowKeys.filter(item => item != record.id);
    state.selectedRows = state.selectedRows.filter(item => item.id != record.id);
  }
};

//单选
const onSelectChange = (selectedRowKeys: Key[], selectedRows: UserRecord[]) => {
  if (props.multiple) return;
  state.selectedRowKeys = selectedRowKeys;
  state.selectedRows = selectedRows;
  closeModal();
  emit("submit", state.selectedRows);
};

const selectedKeys = ref([]);

const [register, { search, refresh }] = useTable({
  columns,
  listApi: props.listApi,
  showAction: false,
  queryParams,
  customRow: customRow,
  rowClassName: "cursor-pointer",
  beforeFetch: (params: Recordable) => {
    params.dept_id = unref(selectedKeys)[0];
  },
  scroll: { y: 300 },
  showTableSetting: false
});

const resetSearch = () => {
  cleanData();
  refresh();
};

const emit = defineEmits(["submit", "register"]);

const cleanData = () => {
  queryParams.key = "";
};

const handleSubmit = () => {
  const selectKey = state.selectedRowKeys;
  if (selectKey.length > 0) {
    closeModal();
    emit("submit", state.selectedRows);
  } else {
    message.error("请选择人员");
  }
};

//加载部门数据
const getDeptData = () => {
  const api = props.deptApi;
  setLoading(true);
  api()
    .then(({ data }: ResponseBody<TreeDataItem[]>) => {
      state.department = data.map(item => ({
        ...item,
        key: item.id,
        title: item.name
      }));
    })
    .finally(() => {
      setLoading(false);
    });
};

//选中部门
const handleSelect = () => {
  search();
};

watch(
  () => props.selectedRows,
  value => {
    if (!value) return;
    state.selectedRowKeys = value.map(item => {
      const id = Number(item.id);
      return !isNaN(id) ? id : item.id;
    });
    state.selectedRows = value;
  },
  {
    deep: true,
    immediate: true
  }
);

onMounted(() => {
  getDeptData();
});
</script>

<style scoped lang="less">
.border-top {
  border-top: 1px solid var(--ant-color-border-secondary);
}

.border-left {
  border-left: 1px solid var(--ant-color-border-secondary);
}
</style>

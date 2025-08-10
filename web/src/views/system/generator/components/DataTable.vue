<template>
  <s-modal
    destroyOnClose
    title="选择表"
    @register="registerModal"
    :width="800"
    :useContainer="false"
    @ok="handleSubmit"
    @cancel="cleanData"
  >
    <div class="h-[450px]">
      <a-form style="padding: 10px 20px" layout="inline" :model="queryParams" @finish="search">
        <s-input label="表名称" v-model="queryParams.table_name" placeholder="请输入表名称搜索" allow-clear/>
        <s-input label="表描述" v-model="queryParams.table_comment" placeholder="请输入表描述搜索" allow-clear/>
        <a-form-item>
          <s-button type="primary" icon="SearchOutlined" html-type="submit"> </s-button>
          <s-button class="ml-[10px]" icon="ReloadOutlined" @click="resetSearch"> </s-button>
        </a-form-item>
      </a-form>
      <s-table
        class="border-top"
        @register="register"
        :rowSelection="{
          selectedRowKeys: state.selectedRowKeys,
          type: multiple ? 'checkbox' : 'radio',
          onChange: onSelectChange
        }"
      />
    </div>
  </s-modal>
</template>

<script setup lang="ts">
import { useTable } from "@/components/Table";
import { useModalInner } from "@/components/Modal";
import { save } from "@/api/system/generator";
import { getAllTable } from "@/api/system/generator";
const { message } = useMessage();
const [registerModal, { closeModal, changeOkLoading }] = useModalInner();
defineProps({
  type: {
    type: String,
    default: ""
  },
  multiple: {
    type: Boolean,
    default: false
  }
});

type Key = number | string;

const queryParams = ref({
  table_name:undefined,
  table_comment:undefined
});

const columns = [
  {
    title: "表名称",
    dataIndex: "name",
    width: 280
  },
  {
    title: "表描述",
    dataIndex: "comment"
  },
  {
    title: "创建时间",
    dataIndex: "create_time",
    width: 180
  }
];
const router = useRouter();
const state = reactive({
  department: [],
  selectedRowKeys: [] as Key[],
  selectedRows: [] as Recordable[]
});

const selectedData = computed(() => {
  return state.selectedRows.map((item: Recordable) => {
    return {
      table_name: item.name,
      table_comment: item.comment
    };
  });
});

const onSelectChange = (selectedRowKeys: Key[], selectedRows: Recordable[]) => {
  state.selectedRowKeys = selectedRowKeys;
  state.selectedRows = selectedRows;
};

const [register, { search, refresh }] = useTable({
  columns,
  listApi: getAllTable,
  showAction: false,
  queryParams,
  scroll: { y: 280 },
  rowKey: "name",
  showTableSetting: false
});

const emit = defineEmits(["save-success", "register"]);

const resetSearch = ()=>{
  cleanData()
  refresh();
}

const cleanData = () => {
  state.selectedRowKeys = [];
  state.selectedRows = [];
  queryParams.value = {
    table_name: undefined,
    table_comment: undefined
  };
};

const handleSubmit = async () => {
  const selectKey = state.selectedRowKeys;
  if (selectKey.length > 0) {
    changeOkLoading(true);
    const data = { table: unref(selectedData) };
    save(data)
      .then(res => {
        if (res.code == 1) {
          message.success(res.msg);
          cleanData();
          closeModal();
          emit("save-success");
          router.push({ path: `/system/generator/${res.data.id}` });
        } else {
          message.error(res.msg);
        }
      })
      .finally(() => {
        changeOkLoading(false);
      });
  } else {
    message.error("请选择表");
  }
};
</script>

<style scoped lang="less">
.border-top {
  border-top: 1px solid var(--ant-color-border-secondary);
}

.border-left {
  border-left: 1px solid var(--ant-color-border-secondary);
}
</style>

<template>
  <page-view>
    <s-table @register="register">
      <template #toolbar>
        <s-button type="primary" icon="delete-outlined" v-auth="'system:operateLog:clear'" @click="onClear">清空</s-button>
        <s-button type="primary"  v-if="selectRowKeysLength" icon="delete-outlined" danger @click="handleDelete"
          >批量删除</s-button
        >
      </template>
      <template #bodyCell="{ value, column }">
        <template v-if="column.dataIndex === 'params'">
          <a-popover placement="top">
            <template #content>
              <highlightjs autodetect :code="value" class="code" />
            </template>
            <a-tag color="blue" class="text-[12px]">查看</a-tag>
          </a-popover>
        </template>
      </template>
    </s-table>
  </page-view>
</template>

<script setup lang="tsx">
import { getOperateLogList, clear as clearApi, destroy } from "@/api/system/operate_log";
import { getActiveUsers } from "@/api/system/user";
import { TableColumnProps } from "@/components/Table";
const { createConfirm, responseNotice } = useMessage();
const columns = [
  {
    title: "id",
    dataIndex: "id"
  },
  {
    title: "操作人",
    dataIndex: "realname",
    columnsType: {
      type: "link"
    }
  },
  {
    title: "操作模块",
    dataIndex: "module"
  },
  {
    title: "操作权限",
    dataIndex: "operate"
  },
  {
    title: "权限节点",
    dataIndex: "route"
  },
  {
    title: "请求方式",
    dataIndex: "method",
    width: 80
  },
  {
    title: "IP地址",
    dataIndex: "ip"
  },
  {
    title: "请求参数",
    dataIndex: "params"
  },
  {
    title: "操作时间",
    dataIndex: "create_time"
  }
];

const columnsUser: TableColumnProps[] = [
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
    title: "名称",
    dataIndex: "realname"
  }
];

const searchForm = ref([
  {
    title: "操作人",
    dataIndex: "user_id",
    component: "TableSelect",
    props: {
      width: 400,
      api: getActiveUsers,
      multiple: false,
      labelField: "realname",
      placeholder:'请选择操作人',
      columns: columnsUser
    }
  },
  {
    title: "请求方式",
    dataIndex: "method",
    component: "Select",
    props: {
      placeholder: "请选择请求方式",
      options: [
        {
          label: "GET",
          value: "GET"
        },
        {
          label: "POST",
          value: "POST"
        },
        {
          label: "PUT",
          value: "PUT"
        },
        {
          label: "DELETE",
          value: "DELETE"
        }
      ]
    }
  },
  {
    title: "操作时间",
    dataIndex: "create_time",
    component: "RangePicker"
  }
]);

const [register, { refresh, handleDelete, selectRowKeysLength }] = useTable({
  columns,
  searchForm,
  listApi: getOperateLogList,
  deleteApi: destroy,
  selection: true,
  showAction: false,
  canResize: true,
  searchSpan:6
});

const onClear = () => {
  createConfirm({
    title: "确定清空全部日志吗?",
    onOk: async () => {
      const res = await clearApi();
      responseNotice(res);
      refresh();
    }
  });
};
</script>
<style scoped lang="less">
:deep(.hljs) {
  background: #f0f0f0;
}
.code {
  border-radius: 5px;
  margin: 5px;
  display: flex;
}
</style>

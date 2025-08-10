<template>
  <page-view showCard showHeader isBack>
    <a-form
      ref="formRef"
      :model="form"
      v-loading="loading"
      :rules="rules"
      :labelCol="{ flex: '100px' }"
      :wrapperCol="{ span: 6 }"
    >
      <a-tabs v-model:activeKey="tabsKey" :animated="{ inkBar: true, tabPane: false }">
        <a-tab-pane :key="1" tab="生成配置">
          <s-input
            name="table_name"
            label="表名称"
            placeholder="请输入表名称"
            v-model="form.table_name"
          ></s-input>
          <s-input
            name="table_comment"
            label="表描述"
            placeholder="请输入表描述"
            v-model="form.table_comment"
          ></s-input>
          <s-input
            name="module_name"
            label="应用目录"
            v-model="form.module_name"
            placeholder="请输入应用目录"
            extra="生成文件所在应用"
          />
          <s-input
            label="模块目录"
            v-model="form.class_dir"
            placeholder="请输入模块目录"
            name="class_dir"
          >
            <template #extra>
              生成文件所在目录名；例：填写test，则控制器文件生成在app/应用目录/controller/test文件夹下。
            </template>
          </s-input>
          <s-radio-group
            v-model="form.generate_type"
            name="generate_type"
            label="生成方式"
            @change="onGenerateType"
          >
            <a-radio :value="0">压缩包下载</a-radio>
            <a-radio :value="1">生成到模块</a-radio>
          </s-radio-group>
          <s-radio-group v-model="form.delete_type" name="delete_type" label="删除方式">
            <a-radio :value="0">真删除</a-radio>
            <a-radio :value="1">软删除</a-radio>
          </s-radio-group>
          <s-tree-select
            label="父级菜单"
            name="pid"
            labelField="title"
            :treeData="treeData"
            placeholder="请选择父级菜单"
            v-model="form.menu_pid"
          />
          <s-input
            name="menu_name"
            label="菜单名称"
            v-model="form.menu_name"
            placeholder="请输入菜单名称"
          />
          <s-radio-group
            v-model="form.menu_type"
            name="menu_type"
            label="菜单构建"
            extra="自动构建：自动执行生成菜单sql。手动添加：自行添加菜单。"
          >
            <a-radio :value="1">自动构建</a-radio>
            <a-radio :value="0">手动添加</a-radio>
          </s-radio-group>
        </a-tab-pane>
        <a-tab-pane :key="2" tab="字段管理">
          <a-table :columns="columns" rowKey="id" :data-source="tableData" :pagination="false">
            <template #bodyCell="{ column, record, index }">
              <template v-if="column.dataIndex === 'comment'">
                <a-input class="w-40" v-model:value="record.comment"></a-input>
              </template>
              <template
                v-if="['is_required', 'is_insert', 'is_edit', 'is_list', 'is_search'].includes(column.dataIndex as string)"
              >
                <a-checkbox v-model:checked="record[column.dataIndex as string]"></a-checkbox>
              </template>
              <template
                v-if="['search_type', 'show_type', 'dict_type'].includes(column.dataIndex as string)"
              >
                <a-select
                  class="w-full"
                  placeholder="请选择"
                  v-model:value="record[column.dataIndex as string]"
                  :options="options[column.dataIndex as string]"
                ></a-select>
              </template>
              <template v-if="column.dataIndex == 'action'">
                <s-confirm-button
                  title="确定要删除吗？"
                  type="link"
                  danger
                  size="small"
                  icon="DeleteOutlined"
                  @confirm="handleDelete(record.id, index)"
                />
              </template>
            </template>
          </a-table>
        </a-tab-pane>
      </a-tabs>
    </a-form>
    <s-footer-toolBar centered>
      <a-space>
        <s-button @click="onSubmit(false)" :loading="spinning">保存</s-button>
        <s-button type="primary" @click="handleGenerator" :loading="generatorLoading"
          >生成代码</s-button
        >
      </a-space>
    </s-footer-toolBar>
  </page-view>
</template>
<script lang="ts" setup>
import { getDictList } from "@/api/system/dict";
import { getMenuList } from "@/api/system/menu";
import { getEdit, update, deleteFiled } from "@/api/system/generator";
import { setFormValue } from "@/utils";
import type { TableColumnProps } from "@/components/Table/types";
import useGenerator from "./useGenerator";
import { RadioChangeEvent } from "ant-design-vue"
const { generatorCode } = useGenerator(false);
interface TreeData {
  value: number;
  title: string;
  children: TreeData[];
}
interface OptionType {
  [key: string]: Array<{ label: string; value: string }>; // 添加索引签名
}
const tabsKey = ref(1);
const route = useRoute();
const router = useRouter();
const { responseNotice, message, createConfirm } = useMessage();

const form = ref({
  id: "",
  table_name: "",
  table_comment: "",
  generate_type: 0,
  module_name: "adminapi",
  class_dir: "",
  menu_pid: 0,
  menu_name: "",
  menu_type: 1,
  delete_type: 0
});

const treeData = ref<TreeData[]>([]);
const formRef = ref();

const rules = reactive({
  table_name: [{ required: true, message: "请输入表名称" }],
  table_comment: [{ required: true, message: "请输入表描述" }],
  template_type: [{ required: true, message: "请选择模版类型" }],
  generate_type: [{ required: true, message: "请选择生成方式" }],
  module_name: [{ required: true, message: "请输入模块名" }],
  menu_pid: [{ required: true, message: "请选择父级菜单" }],
  menu_name: [{ required: true, message: "请输入菜单名称" }],
  menu_type: [{ required: true, message: "请选择菜单构建方式" }],
  class_dir: [{ required: true, message: "请输入类目录" }]
});

const columns: TableColumnProps[] = [
  {
    title: "字段列名",
    dataIndex: "name"
  },
  {
    title: "字段描述",
    dataIndex: "comment",
    width: 200
  },
  {
    title: "字段类型",
    dataIndex: "type"
  },
  {
    title: "必填",
    dataIndex: "is_required"
  },
  {
    title: "插入",
    dataIndex: "is_insert"
  },
  {
    title: "列表",
    dataIndex: "is_list"
  },
  {
    title: "查询",
    dataIndex: "is_search"
  },
  {
    title: "查询方式",
    dataIndex: "search_type",
    width: 120
  },
  {
    title: "组件类型",
    dataIndex: "show_type"
  },
  {
    title: "字典类型",
    dataIndex: "dict_type",
    width: 200
  },
  {
    title: "操作",
    dataIndex: "action"
  }
];

const tableData = ref();

const options = reactive<OptionType>({
  search_type: [
    {
      label: "等于",
      value: "="
    },
    {
      label: "模糊查询",
      value: "like"
    },
    {
      label: "时间范围",
      value: "between time"
    },
    {
      label: "范围查询",
      value: "between"
    },
    {
      label: "IN查询",
      value: "in"
    },
    {
      label: "不等于",
      value: "!="
    },
    {
      label: "大于",
      value: ">"
    },
    {
      label: "大于等于",
      value: ">="
    },
    {
      label: "小于",
      value: "<"
    },
    {
      label: "小于等于",
      value: "<="
    }
  ],
  show_type: [
    {
      label: "文本框",
      value: "input"
    },
    {
      label: "文本域",
      value: "textarea"
    },
    {
      label: "下拉框",
      value: "select"
    },
    {
      label: "单选框",
      value: "radio"
    },
    {
      label: "复选框",
      value: "checkbox"
    },
    {
      label: "日期控件",
      value: "datePicker"
    },
    {
      label: "富文本控件",
      value: "editor"
    }
  ],
  dict_type: []
});

const loading = ref(false);
const spinning = ref(false);

onMounted(() => {
  getDictType();
  getTreeList();
  loadData();
});

const getDictType = () => {
  getDictList({ type: "dict_type" }).then(({ data }) => {
    data.map((item: Recordable) => (item.label = item.name));
    options.dict_type = data;
  });
};

const getTreeList = () => {
  getMenuList().then(res => {
    treeData.value = [];
    const data = { value: 0, title: "顶级菜单", children: [] };
    data.children = res.data;
    treeData.value.push(data);
  });
};

const onGenerateType = (e:RadioChangeEvent) => {
  const { value } = e.target
  if (value == 1) {
    createConfirm({
      title: "温馨提示",
      content: '生成到模块方式如遇同名文件会覆盖旧文件，确定要选择此方式吗？',
      onCancel() {
        form.value.generate_type = 0
      }
    });
  }
};

const handleDelete = async (id: string, index: number) => {
  const res = await deleteFiled(id);
  responseNotice(res);
  if (res.code == 1) {
    tableData.value.splice(index, 1);
  }
};
const generatorLoading = ref(false);
const handleGenerator = async () => {
  generatorLoading.value = true;
  try {
    await onSubmit(true);
    await generatorCode(form.value.id);
  } catch (err) {
    message.error("生成失败");
  } finally {
    generatorLoading.value = false;
  }
};

const loadData = () => {
  loading.value = true;
  const id = route.params.id as string;
  getEdit(id)
    .then(({ data }) => {
      form.value = setFormValue(data, unref(form)) as typeof form.value;
      tableData.value = data.table_column;
    })
    .finally(() => {
      loading.value = false;
    });
};

const onSubmit = async (isGenerator?: boolean): Promise<any> => {
  const data = {
    ...form.value,
    column: tableData.value
  };
  !isGenerator && (spinning.value = true);
  try {
    await formRef.value.validate();
    const res = await update(data);
    !isGenerator && responseNotice(res);
    if (res.code === 1 && !isGenerator) router.back();
    return res;
  } catch (error) {
    if (!(error instanceof Error)) {
      tabsKey.value = 1;
      formRef.value.scrollToField();
    }
    throw error;
  } finally {
    spinning.value = false;
  }
};
</script>

<style lang="less" scoped>
:deep(.ant-form-item-extra) {
  font-size: 12px;
  color: #999999;
  font-family: "PingFang SC, Arial, Hiragino Sans GB, Microsoft YaHei, sans-serif";
  line-height: 24px;
}

:deep(.ant-card-body) {
  margin-bottom: 50px;
}
</style>

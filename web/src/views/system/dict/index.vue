<template>
  <page-view class="h-full">
    <div class="flex dict-container h-full">
      <div class="dict-container-left">
        <header class="dict-header">
          <a-input-search @change="onSearch" placeholder="请输入字典名称搜索" />
        </header>
        <main class="dict-main dict-list">
          <s-scrollbar>
            <a-list size="small" :loading="state.spinning" :data-source="state.data">
              <template #renderItem="{ item }">
                <a-list-item
                  class="dict-list-item"
                  @click="state.currentValue = item"
                  :class="{ active: item.id == state.currentValue?.id }"
                >
                  <span class="custom-dict-node">
                    <span class="name">
                      {{ item.name }}
                      <span class="desc" v-if="item.status == 2">(已禁用)</span>
                    </span>
                    <span class="type">{{ item.value }}</span>
                    <span class="action">
                      <a-tooltip title="编辑">
                        <s-icon type="edit-outlined" @click.stop="openModal(true, item)" />
                      </a-tooltip>
                      <a-tooltip :title="item.status == 2 ? '启用' : '禁用'">
                        <s-icon
                          :type="item.status == 2 ? 'check-circle-outlined' : 'stop-outlined'"
                          @click.stop="updateStatus(item)"
                        />
                      </a-tooltip>
                      <a-tooltip title="删除">
                        <s-icon type="delete-outlined" @click.stop="onDelete(item)" />
                      </a-tooltip>
                    </span>
                  </span>
                </a-list-item>
              </template>
            </a-list>
          </s-scrollbar>
        </main>

        <footer class="dict-footer">
          <s-button icon="sync-outlined" class="mt-[12px]" @click="handleDictUpdate">
            更新缓存
          </s-button>
          <s-button
            type="primary"
            icon="plus-outlined"
            class="mt-[12px]"
            @click="openModal(true, { type: state.dictTypeKey })"
          >
            添加字典
          </s-button>
        </footer>
      </div>
      <section class="dict-container-right">
        <s-table
          ref="tableRef"
          :loading="state.loading"
          :dataSource="state.tableData"
          :columns="columns"
          size="middle"
          :pagination="false"
          table-layout="fixed"
          :can-resize="true"
          :showTableSetting="false"
        >
          <template #headerTop>
            <header class="dict-header-info">
              <div v-if="state.currentValue">
                {{ state.currentValue.name }}
                <a-tag class="type-tag" color="purple">
                  {{ state.currentValue.value }}
                </a-tag>
              </div>
              <s-button
                type="primary"
                icon="plus-outlined"
                @click="openModal(true, { type: state.currentValue.value })"
              >
                添加
              </s-button>
            </header>
            <a-divider></a-divider>
          </template>
          <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex === 'status'">
              <a-tag color="success" v-if="record.status == 1">启用</a-tag>
              <a-tag color="error" v-else>禁用</a-tag>
            </template>
            <template v-if="column.dataIndex === 'action'">
              <a-tooltip title="编辑" @click="openModal(true, record)">
                <s-icon color="#1677ff" class="action-icon" type="form-outlined" />
              </a-tooltip>
              <a-divider type="vertical" />
              <a-tooltip :title="record.status == 2 ? '启用' : '禁用'">
                <s-icon
                  color="#1677ff"
                  :type="record.status == 2 ? 'check-circle-outlined' : 'stop-outlined'"
                  @click.stop="updateStatus(record)"
                />
              </a-tooltip>
              <a-divider type="vertical" />
              <a-tooltip title="删除">
                <s-icon
                  color="#1677ff"
                  type="delete-outlined"
                  @click="onDelete(record)"
                  class="action-icon"
              /></a-tooltip>
              <a-divider type="vertical" />
              <a-tooltip title="排序"
                ><s-icon
                  color="#1677ff"
                  type="menu-outlined"
                  style="cursor: move"
                  class="move-icon"
              /></a-tooltip>
            </template>
          </template>
        </s-table>
      </section>
      <dict-form @register="registerModal" @save-success="refresh" />
    </div>
  </page-view>
</template>

<script setup lang="tsx">
import {
  getDictList,
  destroy,
  updateCache,
  updateSort as updateSortApi,
  changeStatus
} from "@/api/system/dict";
import dictForm from "./form.vue";
import { useSortable } from "@/hooks/useSortable";
import { SortableEvent } from "sortablejs";
import type { TableColumnProps } from "@/components/Table/types";
type DictDataType = {
  id: string;
  name: string;
  value: string | number;
  is_delete: boolean;
  extend: string;
  note: string;
  type: string;
};

const { message, createConfirm, responseNotice } = useMessage();

const columns: TableColumnProps[] = [
  {
    title: "id",
    dataIndex: "id",
    width: 80
  },
  {
    title: "字典名称",
    dataIndex: "name"
  },
  {
    title: "字典类型",
    dataIndex: "type"
  },
  {
    title: "字典值",
    dataIndex: "value",
    customRender: ({ text }) => <a>{text}</a>
  },
  {
    title: "状态",
    dataIndex: "status"
  },
  {
    title: "备注",
    dataIndex: "note"
  },
  {
    title: "操作",
    dataIndex: "action",
    width:140
  }
];

const state = reactive({
  currentValue: {} as Recordable,
  spinning: false,
  loading: false,
  data: [],
  tableData: [],
  dataSource: [],
  dictTypeKey: "dict_type"
});

const [registerModal, { openModal }] = useModal();

watch(
  () => state.currentValue,
  () => {
    loadData();
  }
);

const tableRef = ref();

onMounted(() => {
  state.loading = true;
  getDictType(true);
});

const getDictType = (isInit?: boolean) => {
  state.spinning = true;
  return getDictList({ type: state.dictTypeKey })
    .then(({ data }) => {
      state.dataSource = state.data = data;
      isInit && (state.currentValue = data[0]);
      sortDrag();
    })
    .finally(() => {
      state.spinning = false;
    });
};

const updateSort = () => {
  const data = state.tableData.map((item: DictDataType, index) => {
    return { id: item.id, sort: index + 1 };
  });
  updateSortApi(data);
};

let isFirstLoad = false;

const sortDrag = () => {
  if (isFirstLoad) return;
  const tableElRef = unref(tableRef).tableElRef;
  const el = tableElRef?.$el.querySelector(".ant-table-tbody");
  useSortable(el, {
    handle: ".move-icon",
    onEnd({ newIndex, oldIndex }: SortableEvent) {
      if (oldIndex === undefined || newIndex === undefined || oldIndex === newIndex) return;
      const currRow = state.tableData.splice(oldIndex - 1, 1)[0];
      state.tableData.splice(newIndex - 1, 0, currRow);
      updateSort();
    }
  });
};

const loadData = () => {
  state.loading = true;
  getDictList({ type: state.currentValue.value })
    .then(({ data }) => {
      state.loading = false;
      state.tableData = data;
      isFirstLoad = true;
    })
    .finally(() => {
      state.loading = false;
    });
};

const onSearch = (e: Event) => {
  const { value } = e.target as HTMLInputElement;
  if (value) {
    state.data = state.dataSource.filter((item: DictDataType) => item.name.indexOf(value) !== -1);
  } else {
    state.data = state.dataSource;
  }
};

const refresh = async (model: Recordable) => {
  if (model.type == state.dictTypeKey) {
    await getDictType();
    if (model.id) {
      state.currentValue =
        state.data.find((item: Recordable) => item.id == state.currentValue.id) || {};
    } else {
      state.currentValue = state.data[state.data.length - 1];
    }
  } else {
    loadData();
  }
};

//手动更新缓存
const handleDictUpdate = () => {
  message.loading({ content: "缓存更新中...", key: "key" });
  updateCache().then((res) => {
    if(res.code == 1){
      message.success({ content:  res.msg, key: "key", duration: 1.5 });
    }else{
      message.error({ content: res.msg, key: "key", duration: 1.5 });
    }
  });
};

const onDelete = (data: Recordable) => {
  const isActiveKey = state.currentValue.id == data.id;
  const isDictType = data.type == state.dictTypeKey ? true : false;
  createConfirm({
    title: "提示",
    content: `确定要删除「${data.name}」字典吗?`,
    centered: true,
    onOk: () => {
      return destroy(data.id).then(res => {
        responseNotice(res)
         isDictType ? getDictType() : loadData();
          isActiveKey && (state.currentValue = state.data[0]);
      });
    }
  });
};

//更新状态
const updateStatus = (data: Recordable) => {
  const isDictType = data.type == state.dictTypeKey ? true : false;
  const msg = data.status == 2 ? "启用" : "禁用";
  createConfirm({
    title: "提示",
    content: `确定要${msg}「${data.name}」字典吗?`,
    centered: true,
    onOk: () => {
      return changeStatus(data.id).then(res => {
        responseNotice(res)
        isDictType ? getDictType() : loadData();
      });
    }
  });
};
</script>
<style lang="less" scoped>
.active {
  background-color: var(--ant-color-primary-bg);
  .custom-dict-node .name {
    color: var(--ant-color-primary);
  }
}

.dict-container {
  border-radius: var(--ant-border-radius);
  background: var(--ant-color-bg-container);
}

.dict-box {
  height: 100%;
}

.dict-container-right {
  width: 100%;
  display: flex;
  flex-direction: column;
  padding: 4px;
  overflow: hidden;
}

.dict-container-left {
  border-right: 1px solid var(--ant-color-border-secondary);
  overflow: auto;
  flex-shrink: 0;
  flex: 0 0 300px;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.dict-header {
  flex-shrink: 0;
  height: 60px;
  padding: 13px 15px;
  justify-content: space-between;
}

.dict-main {
  flex: 1;
  flex-basis: auto;
  overflow: auto;
  padding: 20px;
}

.dict-footer {
  display: flex;
  justify-content: space-around;
  border-top: 1px solid var(--ant-color-border-secondary);
  padding: 0 20px;
  flex-shrink: 0;
  height: 60px;
}

.dict-header-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: var(--ant-color-text);
  font-size: 20px;
  font-weight: 500;

  .type-tag {
    position: relative;
    bottom: 3px;
    margin-left: 10px;
  }
}

.dict-category {
  background: var(--ant-color-bg-container);
  padding: 15px 15px;
  border-bottom: 1px solid var(--ant-color-border-secondary);
}

.dict-list {
  padding: 0;
  height: calc(100vh - 370px);

  .dict-list-item {
    cursor: pointer;
    padding: 8px 20px;
    border: none;

    &:not(.active):hover {
      background: var(--ant-color-primary-bg);
    }
  }

  :deep(.ant-spin-dot) {
    margin-top: 100px !important;
  }

  .custom-dict-node {
    display: flex;
    justify-content: space-between;
    width: 100%;

    .type {
      font-size: 12px;
      color: #999;
    }

    span {
      color: var(--ant-color-text-secondary);
      margin: 0 5px;

      &:hover {
        color: #000;
      }
    }

    .desc {
      color: #f62d51;
      margin: 0 5px;
      font-size: 12px;

      &:hover {
        color: #f62d51;
      }
    }

    .action {
      display: none;
      min-width: 72px;
    }

    &:hover {
      .action {
        display: block;
      }

      .type {
        display: none;
      }
    }
  }
}

[data-theme="dark"] {
  .custom-dict-node span:hover {
    color: #fff;
  }
}
</style>

<template>
  <a-tooltip placement="top">
    <template #title>
      <span>列设置</span>
    </template>
    <a-popover
      placement="bottomLeft"
      trigger="click"
      overlayClassName="cloumn-list"
      @openChange="onVisibleChange"
    >
      <template #title>
        <div class="popover-title">
          <a-checkbox
            :indeterminate="indeterminate"
            v-model:checked="state.checkAll"
            @change="onCheckAllChange"
          >
            列展示
          </a-checkbox>
          <a-button @click="resetColumns" style="float: right" type="link" size="small"
            >重置
          </a-button>
        </div>
      </template>
      <template #content>
        <div ref="sort" class="sort">
          <a-list size="small" :key="item.dataIndex" v-for="item in columns">
            <a-list-item class="col-item">
              <s-icon type="holder-outlined" class="move-icon" />
              <a-checkbox
                v-model:checked="item.visible"
                style="margin-right: 10px"
                @change="onCheckChange"
              />
              <template v-if="item.title">
                {{ item.title }}
              </template>
            </a-list-item>
          </a-list>
        </div>
      </template>
      <s-icon type="setting-outlined" :size="13" />
    </a-popover>
  </a-tooltip>
</template>
<script lang="ts" setup>
import { useTableContext } from "../../hooks";
import { cloneDeep } from "lodash-es";
import { useSortable } from "@/hooks/useSortable";
import { Ref } from "vue";
import { SortableEvent } from "sortablejs";

type Columns = {
  dataIndex?: string | number;
  visible?: boolean;
  title?: string;
  [propName: string]: any;
};

const table = useTableContext();

const state = reactive({
  checkAll: true,
  defaultCheckList: []
});

let isFirstLoad = false;

const sort = ref();

const onVisibleChange = async () => {
  if (isFirstLoad) return;
  await nextTick();
  useSortable(sort.value, {
    handle: ".move-icon",
    onEnd({ newIndex, oldIndex }: SortableEvent) {
      const currRow = columns.value.splice(oldIndex as number, 1)[0];
      columns.value.splice(newIndex as number, 0, currRow);
      table.setColumns(checkedList.value, false);
    }
  });
  isFirstLoad = true;
};

//显示的列
const checkedList = computed(() => {
  return columns.value.filter(item => item.visible);
});

const columns = ref<Columns[]>([]); //列数据

const indeterminate = computed(() => {
  const len = columns.value.length;
  const checkedLen = unref(checkedList).length;
  return checkedLen > 0 && checkedLen < len;
});

onMounted(() => {
  const column = setColumnsVisible(table.getColumns, true);
  columns.value = cloneDeep(column);
  state.defaultCheckList = cloneDeep(column);
});

const onCheckAllChange = (e: { target: { checked: boolean } }) => {
  if (e.target.checked) {
    setColumnsVisible(columns, true);
    table.setColumns(state.defaultCheckList);
  } else {
    setColumnsVisible(columns, false);
    table.setColumns([]);
  }
};

const setColumnsVisible = (data: Ref<any>, visible: boolean) => {
  unref(data).forEach((item: { visible: boolean }) => {
    item.visible = visible;
  });
  return unref(data);
};

const onCheckChange = () => {
  const len = unref(columns).length;
  state.checkAll = unref(checkedList).length === len;
  table.setColumns(unref(checkedList), false);
};

//重置列
const resetColumns = () => {
  state.checkAll = true;
  columns.value = cloneDeep(state.defaultCheckList);
  table.setColumns(unref(columns));
};
</script>

<style lang="less">
.cloumn-list {
  .ant-popover-inner-content {
    max-height: 325px;
    overflow-y: auto;
    padding: 0;
  }
  .col-item {
    justify-content: start;
    padding: 5px 16px;
  }
}

.move-icon {
  margin-right: 15px;

  &:hover {
    cursor: move;
  }
}
</style>

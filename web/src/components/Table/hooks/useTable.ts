import { getDynamicProps, isProdMode } from "@/utils";
import type { WatchStopHandle } from "vue";
import { isArray } from "@/utils/is";
import type { BasicTableProps, TableActionType } from "../types";
type Props = Partial<DynamicProps<BasicTableProps>>;
export function useTable(
  tableProps: Props
): [(instance: TableActionType) => void, TableActionType] {
  const { message, createConfirm } = useMessage();

  const tableRef = ref<Nullable<TableActionType>>(null);

  let stopWatch: WatchStopHandle;

  //是否已加载
  const loadedRef = ref<boolean | null>(false);

  const register = (instance: TableActionType) => {
    //防止重复加载执行
    if (isProdMode()) {
      onUnmounted(() => {
        tableRef.value = null;
        loadedRef.value = null;
      });
    }

    if (unref(loadedRef) && isProdMode() && instance === unref(tableRef)) return;

    tableRef.value = instance;
    tableProps && instance.setProps(getDynamicProps(tableProps));
    loadedRef.value = true;

    stopWatch?.();

    stopWatch = watch(
      () => tableProps,
      () => {
        tableProps && instance.setProps(getDynamicProps(tableProps));
      },
      {
        immediate: true,
        deep: true
      }
    );
  };

  const getTableInstance = (): TableActionType => {
    const table = unref(tableRef);
    if (!table) {
      console.error("The table instance has not been obtained yet");
    }
    return table as TableActionType;
  };

  //删除
  const handleDelete = async (id?: Array<string | number> | number | string, failRefresh: boolean = false) => {
    const { deleteApi } = tableProps;
    if (!deleteApi) return;
    const rowkey = id && (isArray(id) || !isNaN(Number(id))) ? id : getTableInstance().getSelectRowKeys();
    //批量删除
    if (isArray(rowkey)) {
      if (!rowkey.length) return message.error("请选择要删除的数据");
      createConfirm({
        title: "提示",
        content: `你确定要删除这 ${rowkey.length} 项吗?`,
        onOk: () => deleteAction(deleteApi, rowkey, failRefresh)
      });
    } else {
      await deleteAction(deleteApi, rowkey, failRefresh);
    }
  };

  const deleteAction = async (request: Fn, rowkey: number | string | Array<string | number>, failRefresh: boolean) => {
    try {
      const res = await request(rowkey);
      if (res.code == 1) {
        message.success(res.msg, 1);
        getTableInstance().search();
      } else {
        message.error(res.msg, 2);
        if (failRefresh) {
          getTableInstance().search();
        }
      }
    } catch {
      message.destroy();
    }
  };


  const methods: TableActionType = {
    setProps: props => {
      getTableInstance().setProps(props);
    },
    refresh: opt => {
      getTableInstance().refresh(opt);
    },
    search: opt => {
      getTableInstance().search(opt);
    },
    clearSelectedRowKeys: () => {
      getTableInstance().clearSelectedRowKeys();
    },
    getSelectRows: () => {
      return getTableInstance().getSelectRows();
    },
    getSelectRowKeys: () => {
      return getTableInstance()?.getSelectRowKeys();
    },
    handleDelete: args => {
      return handleDelete(args);
    },
    redoHeight: () => {
      return getTableInstance().redoHeight();
    },
    getFormValue: () => {
      return getTableInstance().getFormValue();
    },
    getSize: () => {
      return getTableInstance().getSize();
    },
    getDataSource: () => {
      return getTableInstance().getDataSource();
    },
    setColumns: col => {
      return getTableInstance().setColumns(col);
    },
    doSearchLayout: () => {
      return getTableInstance().doSearchLayout();
    },
    getColumns: computed(() => {
      return getTableInstance().getColumns || [];
    }),
    selectRowKeysLength: computed(() => {
      return getTableInstance()?.getSelectRowKeys().length;
    })
  };

  return [register, methods];
}

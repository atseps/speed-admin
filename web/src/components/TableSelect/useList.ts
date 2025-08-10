import { isFunction } from "@/utils/is";
import type { TableProps, TablePaginationConfig } from "ant-design-vue";
export function useList(api: (params: Record<string, any>) => Promise<any> | undefined, params: Recordable = {}) {
  const loading = ref(false);
  const pageSize = ref(15);
  const current = ref(1);
  const total = ref(0);
  const tableData = ref([]);

  const pagination = computed<TablePaginationConfig>(() => {
    return {
      current: unref(current),
      pageSize: unref(pageSize),
      total: unref(total),
      size: "small",
      showSizeChanger: false,
      showTotal: (total: number) => `共有 ${total} 条数据`
    };
  });

  //分页、排序、筛选变化时触发
  const handleChange: TableProps["onChange"] = pagination => {
    current.value = pagination.current as number;
    pageSize.value = pagination.pageSize as number;
    loadData();
  };

  const searchParams = computed(() => {
    return {
      page: unref(current),
      pageSize: unref(pageSize),
      ...unref(params)
    };
  });

  const setPageCurrent = (val: number) => {
    current.value = val;
  };

  const loadData = async () => {
    if (api && isFunction(api)) {
      loading.value = true;
      try {
        const { data: res } = await api(unref(searchParams));
        tableData.value = res.data;
        total.value = res.total;
      } catch (error) {
        tableData.value = [];
        total.value = 0;
      } finally {
        loading.value = false;
      }
    }
  };

  return {
    pagination,
    tableData,
    loadData,
    handleChange,
    loading,
    setPageCurrent
  };
}

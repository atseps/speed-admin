import type { ComputedRef } from "vue";
import { isFunction } from "@/utils/is";
import type { TableProps, PaginationProps } from "ant-design-vue";
import type { BasicTableProps } from "../types";
import { tableConfig } from "@/config";
const { pageField, sizeField, listField, totalField, defPageSize } = tableConfig;

interface SearchState {
  sortInfo: Recordable;
  filterInfo: Recordable;
}

interface ActionType {
  getPagination: ComputedRef<boolean | PaginationProps>;
  setPagination: (info: Partial<PaginationProps>) => void;
  setPageVisible: (visible: boolean) => void;
  setLoading: (loading: boolean) => void;
  clearSelectedRowKeys: () => void;
  getFormValue: () => Recordable;
}

export function useDataSource(
  props: ComputedRef<BasicTableProps>,
  {
    getPagination,
    setPagination,
    setPageVisible,
    setLoading,
    clearSelectedRowKeys,
    getFormValue
  }: ActionType,
  emit: ReturnType<typeof defineEmits>
) {
  //表格数据
  const tableData = ref<Recordable[]>([]);
  //受控的筛选和排序
  const searchState = reactive<SearchState>({
    sortInfo: {},
    filterInfo: {}
  });

  const getCanResize = computed(() => {
    const { canResize, scroll } = unref(props);
    return canResize && !(scroll || {}).y;
  });

  watch(
    () => unref(props).dataSource,
    () => {
      const { dataSource, listApi } = unref(props);
      !listApi && dataSource && (tableData.value = dataSource);
    },
    {
      immediate: true
    }
  );

  //分页、排序、筛选变化时触发
  const handleChange: TableProps["onChange"] = async (pagination, filters, sorter) => {
    const params: Recordable = {};

    //设置分页参数
    setPagination(pagination);

    //筛选
    if (filters) {
      searchState.filterInfo = filters;
      Object.assign(params, { ...filters });
    }

    //排序
    if (sorter) {
      const sortInfo = handleSort(sorter);
      searchState.sortInfo = sortInfo;
      const sortParams = {
        ...sortInfo,
        order: sortInfo.order?.replace("end", "")
      };
      Object.assign(params, sortParams);
    }

    //搜索表单参数
    const searchParams = getFormValue() || {};
    await loadData(Object.assign(params, searchParams));
    handleScrollTo();
  };

  const handleSort = (sortInfo: Recordable) => {
    const { field, order } = sortInfo;
    if (field && order) {
      return { field, order };
    } else {
      return {};
    }
  };

  //加载数据
  const loadData = async (opt?: Recordable) => {
    const {
      listApi, //请求接口
      queryParams, //自定义的请求参数
      beforeFetch, //请求之前的处理函数
      afterFetch, //请求之后的处理函数
      pagination, //自定义传递的分页参数
      showLoading //请求是否显示加载动画
    } = unref(props);

    if (!listApi || !isFunction(listApi)) return;

    try {
      //分页参数
      let pageParams: Recordable = {};

      //获取分页的参数
      let { current = 1, pageSize = defPageSize } = unref(getPagination) as PaginationProps;
      if (pagination === false || unref(getPagination) === false) {
        pageParams = {};
      } else {
        pageParams[pageField] = current;
        pageParams[sizeField] = pageSize;
      }

      //过滤、排序参数
      const { sortInfo = {}, filterInfo } = searchState;

      //实际请求参数
      let params: Recordable = {
        ...pageParams, //分页参数
        ...queryParams, //组件传递的查询参数
        ...sortInfo, //排序参数
        ...filterInfo, //过滤参数
        ...(opt ?? {}) //方法调用传递的查询参数
      };

      //请求之前的处理函数
      if (beforeFetch && isFunction(beforeFetch)) {
        params = (await beforeFetch(params)) || params;
      }

      showLoading && setLoading(true);

      //发起请求
      const { data: res } = await listApi(params);

      const isArray = Array.isArray(res);

      let data: Recordable[] = [];

      if (isArray) {
        //直接返回数组，没有分页
        data = res;
        setPageVisible(false);
      } else {
        data = res[listField];
      }

      const total: number = isArray ? 0 : res[totalField];

      // 为防止删除数据后导致页面当前页面数据长度为 0 ,自动翻页到上一页
      if (data.length === 0 && pagination != false && current > 1) {
        setPagination({
          current: --current
        });
        return await loadData(opt);
      }

      if (afterFetch && isFunction(afterFetch)) {
        data = (await afterFetch(data)) || data;
      }

      tableData.value = data;

      //设置分页总数
      setPagination({ total: total || 0 });

      emit("load-success", tableData.value);
      return data;
    } catch (error) {
      emit("load-error", error);
      tableData.value = [];
      setPagination({ total: 0 });
    } finally {
      setLoading(false);
    }
  };

  onMounted(() => {
    setTimeout(() => {
      unref(props).immediate && loadData();
    }, 16);
  });

  //处理分页后滚动
  const handleScrollTo = () => {
    const { scrollTo } = unref(props);
    if (scrollTo && !unref(getCanResize)) {
      window.scrollTo(0, 0);
    }
  };

  //清除排序和过滤
  const clearSortFilter = () => {
    searchState.filterInfo = {};
    searchState.sortInfo = {};
  };

  //表格刷新
  const refresh = (opt?: Recordable, reset = true) => {
    //重置分页到第一页
    setPagination({ current: 1 });
    //清除排序、清除过滤
    reset && clearSortFilter();
    //清除选择
    clearSelectedRowKeys();
    //请求数据
    nextTick(() => loadData(opt));
  };

  //表格搜索
  const search = (opt: Recordable = {}, reset = false) => {
    const searchParams = getFormValue() || {};
    //重置分页到第一页
    reset && setPagination({ current: 1 });
    //清除选择
    clearSelectedRowKeys();
    loadData(Object.assign(opt, searchParams));
  };

  const getDataSource = <T = Recordable>() => {
    return tableData.value as T[];
  };

  return {
    tableData,
    handleChange,
    refresh,
    search,
    searchState,
    getDataSource
  };
}

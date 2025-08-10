import { tableConfig } from "@/config";
import type { ComputedRef } from "vue";
import type { BasicTableProps } from "../types";
import { PaginationProps } from "ant-design-vue";
const { defPageSize, pageSizeOptions } = tableConfig;

export function usePagination(props: ComputedRef<BasicTableProps>) {
  //控制分页是否可见
  const visible = ref(true);

  //内部的分页配置
  const innerPagination = ref({});

  //设置分页数据
  const setPagination = (pagination: PaginationProps) => {
    const paginationInfo = unref(getPagination) as PaginationProps;
    innerPagination.value = {
      ...paginationInfo,
      ...pagination
    };
  };

  //获取分页配置
  const getPagination = computed((): PaginationProps | boolean => {
    //prop传进来的分页配置
    const { pagination } = unref(props);
    if (!unref(visible) || pagination === false) {
      return false;
    }

    return {
      current: 1,
      pageSize: defPageSize,
      size: "small",
      showTotal: (total: number) => `共有 ${total} 条数据`,
      showSizeChanger: true,
      pageSizeOptions: pageSizeOptions,
      showQuickJumper: true,
      ...pagination,
      ...unref(innerPagination)
    };
  });

  //设置分页是否可见
  const setPageVisible = (value: boolean) => {
    visible.value = value;
  };

  return {
    getPagination,
    setPagination,
    setPageVisible
  };
}

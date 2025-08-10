import type { ComputedRef } from "vue";
import { omit } from "lodash-es";
import { isBoolean } from "@/utils/is";
import type { BasicTableProps } from "../types";
import type { Key } from "ant-design-vue/lib/table/interface";
export function useRowSelection(props: ComputedRef<BasicTableProps>) {
  const selectedRowKeys = ref<Key[]>([]); //选中的key
  const selectedRow = ref<Recordable[]>([]); //选中的行

  const getRowSelection = computed(() => {
    if (unref(props).selection) {
      return {
        selectedRowKeys: unref(selectedRowKeys),
        onChange: (selectedRowKeys: string[], selectedRows: Recordable[]) => {
          setSelectedRowKeys(selectedRowKeys, selectedRows);
        },
        ...(isBoolean(unref(props).selection)
          ? {}
          : omit(unref(props).selection as Recordable, ["onChange"]))
      };
    }
    return unref(props).rowSelection;
  });

  const setSelectedRowKeys = (rowKeys: Key[], selectedRows: Recordable[]) => {
    selectedRowKeys.value = rowKeys; //选中的key
    selectedRow.value = selectedRows; //选中的行
  };

  //清除选中
  const clearSelectedRowKeys = () => {
    selectedRow.value = [];
    selectedRowKeys.value = [];
  };

  //获取选中的key
  const getSelectRowKeys = () => {
    return unref(selectedRowKeys);
  };

  //获取选中的行
  const getSelectRows = () => {
    return unref(selectedRow);
  };

  return {
    getRowSelection,
    clearSelectedRowKeys,
    getSelectRows,
    getSelectRowKeys
  };
}

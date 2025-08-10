import type { Ref, ComputedRef } from "vue";
import type { BasicTableProps, TableActionType } from "../types";
const key = Symbol("basic-table");

type Instance = Omit<TableActionType, "handleDelete" | "handleExport"> & {
  tableElRef: Ref<Nullable<HTMLElement>>;
  wrapRef: Ref<Nullable<HTMLElement>>;
  getTableProps: ComputedRef<Recordable>;
};

type RetInstance = Omit<Instance, "getTableProps"> & {
  getTableProps: ComputedRef<BasicTableProps>;
};

export function createTableContext(instance: Instance) {
  provide(key, instance);
}

export function useTableContext(): RetInstance {
  return inject(key) as RetInstance;
}

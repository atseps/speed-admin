import type { ComputedRef } from "vue";
import { cloneDeep } from "lodash-es";
import type { PaginationProps } from "ant-design-vue";
import type { BasicTableProps, SearchState, TableColumnProps, columnProps } from "../types";
import Ellipsis from "@/components/Ellipsis";
import { isNumber, isBoolean, isFunction, isArray } from "@/utils/is";
import { Image, Tag } from "ant-design-vue";
import dayjs from "dayjs";
import DictLabel from "@/components/DictLabel";
import { tableConfig } from "@/config";

interface RenderParams {
  index: number;
  value?: any;
  record?: Recordable;
}

export function useColumns(
  props: ComputedRef<BasicTableProps>,
  pagination: ComputedRef<boolean | PaginationProps>,
  searchState: SearchState
) {
  const columnsRef = ref<TableColumnProps[]>();

  const createColumn = ref(true);

  watchEffect(() => {
    columnsRef.value = unref(props).columns;
  });

  //获取列
  const getColumns = computed(() => {
    const columns = cloneDeep(unref(columnsRef)) as TableColumnProps[];
    if (unref(createColumn)) {
      //是否显示序号
      handleIndexColumn(props, pagination, columns);
      //处理操作列
      handleActionColumn(props, columns);
    }
    //处理列
    handleColumn(columns, searchState);

    return columns;
  });

  const getViewColumns = computed(() => {
    const viewColumns = unref(getColumns);
    const columns = cloneDeep(viewColumns);
    function buildColumns(columns: TableColumnProps[]) {
      return columns.map(column => {
        if (column.children) {
          buildColumns(column.children);
        }
        return reactive(column);
      });
    }
    return buildColumns(columns);
  });

  //设置列
  const setColumns = (columnList: TableColumnProps[], createCol = true) => {
    const columns = cloneDeep(columnList);
    if (!isArray(columns)) return;

    if (columns.length > 0) {
      createColumn.value = createCol;
      columnsRef.value = columns;
    } else {
      columnsRef.value = [{}];
      //列设置为空时 显示序号
      if (!unref(props).showIndex) {
        handleIndexColumn({ showIndex: true }, pagination, columnsRef.value);
      }
      createColumn.value = false;
    }
  };

  //渲染文字省略
  const renderTooltip = (value: string, column: TableColumnProps) => {
    const maxTextLength = typeof column.maxLength === 'number' ? column.maxLength : 0;
    return (
      <Ellipsis length={maxTextLength} tooltip={true}>
        {{ default: () => value }}
      </Ellipsis>
    );
  };

  const handleColumn = (columns: TableColumnProps[], searchState: SearchState) => {
    const { sortInfo, filterInfo } = toRefs(searchState);
    if (columns.length <= 0) return;
    columns.forEach((column: TableColumnProps) => {
      if (column.width) {
        column.resizable = column.resizable || true;
      }
      //受控的排序和筛选
      if (isBoolean(column.sorter) && column.sorter) {
        column.sortOrder = sortInfo.value?.order;
      }

      if (column.filters && column.filters.length > 0) {
        column.filteredValue = filterInfo.value[column.dataIndex as string] || null;
      }

      //文本最大显示长度，超出显示省略号并使用 Tooltip 显示
      if (column.maxLength && isNumber(column.maxLength)) {
        column.customRender = ({ value }) => renderTooltip(value, column);
      }

      const type = column.props?.type;
      if (type && ["link", "img", "date", "yesNo", "tag", "dict"].includes(type)) {
        column.customRender = ({ value, record }: RenderParams) => {
          if (type == "link") {
            const onClick = () => {
              const { onClick: clickFn } = column?.props as columnProps;
              if (clickFn && isFunction(clickFn)) {
                clickFn(record);
              }
            };
            return (
              <a {...column.props} onClick={onClick}>
                {column.maxLength ? renderTooltip(value, column) : value}
              </a>
            );
          } else if (type == "img") {
            const { width = 60, height = 60 } = column?.props as columnProps;
            return <Image src={value} width={width} height={height} {...column.props} />;
          } else if (type == "date") {
            const { format = "YYYY-MM-DD" } = column.props as columnProps;
            return <span {...column.props}>{value && dayjs(value).format(format)}</span>;
          } else if (type == "yesNo") {
            return <span {...column.props}>{value ? "是" : "否"}</span>;
          } else if (type == "dict") {
            return <DictLabel dict={value}></DictLabel>;
          } else if (type == "tag") {
            return <Tag {...column.props}>{value}</Tag>;
          }
        };
      }
    });
  };

  //显示列序号
  const handleIndexColumn = (
    props: ComputedRef<BasicTableProps> | { showIndex: boolean },
    pagination: ComputedRef<boolean | PaginationProps>,
    columns: TableColumnProps[]
  ) => {
    const { showIndex } = unref(props);

    if (columns.length <= 0 || !showIndex) return;

    const isFixedLeft = columns.some((item: TableColumnProps) => item.fixed === "left");

    const hasIndex = columns.findIndex((item: TableColumnProps) => item.dataIndex === "tableIndex");
    if (hasIndex != -1) return;

    columns.unshift({
      width: 50,
      title: "序号",
      align: "center",
      dataIndex: "tableIndex",
      customRender: ({ index }: RenderParams) => {
        const getPagination = unref(pagination);
        if (getPagination === false) {
          return `${index + 1}`;
        }
        const { current = 1, pageSize = tableConfig.defPageSize } =
          getPagination as PaginationProps;
        return ((current < 1 ? 1 : current) - 1) * pageSize + index + 1;
      },
      ...(isFixedLeft ? { fixed: "left" } : {})
    });
  };

  const handleActionColumn = (props: ComputedRef<BasicTableProps>, columns: TableColumnProps[]) => {
    const { showAction, actionColumn } = unref(props);

    if (!showAction || !columns.length) return;

    const hasIndex = columns.findIndex((item: TableColumnProps) => item.dataIndex === "action");

    if (hasIndex === -1) {
      columns.push({
        title: "操作",
        dataIndex: "action",
        fixed: "right",
        ...actionColumn
      });
    }
  };

  return {
    getColumns,
    getViewColumns,
    setColumns
  };
}

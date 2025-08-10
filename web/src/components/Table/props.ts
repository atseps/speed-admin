import { propTypes } from "@/utils/propTypes";
import type { TableRowSelection } from "ant-design-vue/lib/table/interface";
import type { TableColumnType, PaginationProps, TableProps } from "ant-design-vue";
import type { TableColumnProps } from "./types";

export const basicProps = {
  //列表接口
  listApi: {
    type: Function as PropType<(...arg: any[]) => Promise<any>>,
    default: null
  },
  //删除接口
  dateleApi: {
    type: Function as PropType<(...arg: any[]) => Promise<any>>,
    default: null
  },
  //请求之前处理的函数
  beforeFetch: {
    type: Function as PropType<Function>,
    default: null
  },
  //请求之后的处理函数
  afterFetch: {
    type: Function as PropType<Function>,
    default: null
  },
  // 立即请求接口
  immediate: propTypes.bool.def(true),
  // 额外的请求参数
  queryParams: {
    type: Object as PropType<Recordable>,
    default: null
  },
  //表格列
  columns: {
    type: Array as PropType<TableColumnProps[]>,
    default: () => []
  },
  //显示序号
  showIndex: propTypes.bool.def(false),
  //显示操作列
  showAction: propTypes.bool.def(true),
  //多选配置
  rowSelection: {
    type: Object as PropType<TableRowSelection | null>,
    default: null
  },
  //表格数据源
  dataSource: {
    type: Array as PropType<Recordable[]>,
    default: null
  },
  actionColumn: {
    type: Object as PropType<TableColumnType[]>,
    default: null
  },
  //行主键
  rowKey: {
    type: [String, Function] as PropType<string | ((record: Recordable) => string)>,
    default: "id"
  },
  //分页
  pagination: {
    type: [Object, Boolean] as PropType<PaginationProps | boolean>,
    default: null
  },
  //是否多选
  selection: {
    type: [Object, Boolean] as PropType<Recordable | boolean>
  },
  //滚动配置
  scroll: {
    type: Object as PropType<TableProps["scroll"]>,
    default: null
  },
  //表格大小
  size: propTypes.string.def("small"),
  //表格布局
  tableLayout: propTypes.string.def("auto"),
  //加载动画
  loading: propTypes.bool,
  //请求是否显示加载动画
  showLoading: propTypes.bool.def(true),
  //自定义渲染展开图标
  customExpandIcon: propTypes.bool.def(false),
  //分页时页面滚动到顶部
  scrollTo: propTypes.bool.def(true),
  //最大高度
  maxHeight: propTypes.number,
  //是否自适应高度
  canResize: propTypes.bool.def(false),
  //搜索表单
  searchForm: propTypes.array,
  //搜索表单label宽度
  labelWidth: propTypes.string.def("80px"),
  //是否显示头部操作栏按钮
  showTableSetting: propTypes.bool.def(true),
  //搜索表单折叠状态下默认显示的表单控件数量
  defaultColsNumber: propTypes.number,
  // 搜索表单配置列数
  searchSpan: propTypes.number,
  // 表格高度偏移量
  resizeHeightOffset: propTypes.number,
  debounceRender: propTypes.bool.def(true)
};

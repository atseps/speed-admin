import { PaginationProps, TableColumnType, TableProps } from "ant-design-vue";
import type { SearchFormType } from "@/components/SearchForm/typing";
import type { ComputedRef,CSSProperties } from "vue";
import type { Key } from "ant-design-vue/lib/table/interface";
import type { ColumnProps as AntColumnProps } from "ant-design-vue/lib/table";

export interface SearchState {
  sortInfo: Recordable;
  filterInfo: Record<string, string[]>;
}

export interface columnProps {
  type?: "link" | "img" | "date" | "dict" | "tag" | "yesNo" | (string & {});
  onClick?: Fn;
  width?: number;
  height?: number;
  format?: string;
}

export interface TableColumnProps extends AntColumnProps<Recordable> {
  // 文本最大显示长度，超出则显示省略号和Tooltip
  maxLength?: number;
  //扩展属性
  props?: columnProps;

  children?: TableColumnProps[];
}

export type SizeType = TableProps["size"];

export interface TableActionType {
  setProps: (props: Partial<BasicTableProps>) => void;
  refresh: (opt?: Recordable) => void;
  search: (opt?: Recordable) => void;
  getSelectRows: () => Recordable[];
  clearSelectedRowKeys: () => void;
  getSelectRowKeys: () => Key[];
  handleDelete: (id?: any, failRefresh?: boolean) => Promise<Recordable<any>[] | undefined>;
  getDataSource: <T = Recordable>() => T[];
  getFormValue: <T = Recordable>() => T[];
  setColumns: (col: TableColumnProps[], create?: boolean) => void;
  redoHeight: () => void;
  getColumns: ComputedRef;
  selectRowKeysLength?: ComputedRef;
  getSize: () => SizeType;
  doSearchLayout:() => void;
}

export interface BasicTableProps extends TableProps {
  //列表接口
  listApi?: (...arg: any) => Promise<any>;
  //删除接口
  deleteApi?: (...arg: any) => Promise<any>;
  //请求之前处理的函数
  beforeFetch?: Function;
  //请求之后的处理函数
  afterFetch?: Function;
  // 立即请求接口
  immediate?: boolean;
  // 额外的请求参数
  queryParams?: Recordable;
  //表格列
  columns?: TableColumnProps[];
  //显示序号
  showIndex?: boolean;
  //显示操作列
  showAction?: boolean;
  //操作列
  actionColumn?: TableColumnType[];
  //分页
  pagination?: PaginationProps | false;
  //是否多选
  selection?: Recordable | boolean;
  rootStyle?: CSSProperties;
  //请求是否显示加载动画
  showLoading?: boolean;
  //自定义渲染展开图标
  customExpandIcon?: boolean;
  //分页时页面滚动到顶部
  scrollTo?: boolean;
  //最大高度
  maxHeight?: number;
  //是否自适应高度
  canResize?: boolean;
  //搜索表单
  searchForm?: SearchFormType[] | DynamicProps<SearchFormType[]>;
  //搜索表单label宽度
  labelWidth?: string;
  //是否显示头部操作栏按钮
  showTableSetting?: boolean;
  //搜索表单折叠状态下默认显示的表单控件数量
  defaultColsNumber?: number;
  //搜索表单配置列数
  searchSpan?: number;
  // 表格高度偏移量
  resizeHeightOffset?: number;

  debounceRender?: boolean;
}

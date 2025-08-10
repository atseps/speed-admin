import type { ButtonProps } from "ant-design-vue/lib/button/buttonTypes";
import type { CSSProperties, VNodeChild, ComputedRef } from "vue";
import { ButtonType } from "ant-design-vue/es/button";
import type { DrawerProps as ADrawerProps } from "ant-design-vue";
export interface DrawerInstance {
  setDrawerProps: (props: Partial<DrawerProps>) => void;
  emitVisible?: (visible: boolean, uid: number) => void;
  props?: any;
}

export interface ReturnMethods extends DrawerInstance {
  openDrawer: <T = any>(visible?: boolean | any, data?: T, openOnSet?: boolean) => void;
  closeDrawer: () => void;
  getVisible: ComputedRef<boolean>;
}

export type RegisterFn = (drawerInstance: DrawerInstance, uuid?: string) => void;

export interface ReturnInnerMethods extends DrawerInstance {
  closeDrawer: () => void;
  changeLoading: (loading: boolean) => void;
  changeOkLoading: (loading: boolean) => void;
  getVisible?: ComputedRef<boolean>;
}

export type UseDrawerReturnType = [RegisterFn, ReturnMethods];

export type UseDrawerInnerReturnType = [RegisterFn, ReturnInnerMethods];

export interface DrawerFooterProps {
  // 是否显示确定按钮
  showOkBtn: boolean;
  // 是否显示取消按钮
  showCancelBtn: boolean;
  /**
   * 取消按钮的文本
   * @default 'cancel'
   * @type string
   */
  cancelText: string;
  /**
   * 确定按钮的文本
   * @default 'OK'
   * @type string
   */
  okText: string;

  /**
   * 确定按钮的类型
   * @default 'primary'
   * @type string
   */
  okType: ButtonType;
  /**
   * 确定按钮的属性，遵循 JSX 规则
   * @type object
   */
  okButtonProps: { props: ButtonProps; on: {} };

  /**
   * 取消按钮的属性，遵循 JSX 规则
   * @type object
   */
  cancelButtonProps: { props: ButtonProps; on: {} };
  /**
   * 确定按钮是否显示加载状态
   * @default false
   * @type boolean
   */
  confirmLoading: boolean;

  // 是否显示底部区域
  showFooter: boolean;
  // 底部区域的高度
  footerHeight: string | number;
}

/**
 * 抽屉属性接口，继承自 DrawerFooterProps
 */
export interface DrawerProps extends DrawerFooterProps {
  // 加载状态
  loading?: boolean;
  // 是否打开
  open?: boolean;
  // 是否显示返回详情按钮
  showDetailBack?: boolean;
  // 可见性状态
  visible?: boolean;
  /**
   * 内置 ScrollContainer 组件的配置
   * @type ScrollContainerOptions
   */
  scrollOptions?: any;
  // 关闭抽屉的回调函数，返回一个 Promise
  closeFunc?: () => Promise<any>;

  // 请求函数
  request?: Function;

  // 是否显示加载状态
  showLoading?: boolean;

  // 是否触发窗口大小调整事件
  triggerWindowResize?: boolean;
  /**
   * 抽屉右上角是否显示关闭按钮
   * @default true
   * @type boolean
   */
  closable?: boolean;

  /**
   * 关闭抽屉时是否卸载子组件
   * @default false
   * @type boolean
   */
  destroyOnClose?: boolean;

  /**
   * 返回抽屉挂载的节点
   * @default 'body'
   * @type any ( HTMLElement| () => HTMLElement | string)
   */
  getContainer?: ADrawerProps["getContainer"];

  /**
   * 是否显示遮罩层
   * @default true
   * @type boolean
   */
  mask?: boolean;

  /**
   * 点击遮罩层是否关闭抽屉
   * @default true
   * @type boolean
   */
  maskClosable?: boolean;

  /**
   * 遮罩层的样式
   * @default {}
   * @type object
   */
  maskStyle?: CSSProperties;

  /**
   * 抽屉的标题
   * @type any (string | slot)
   */
  title?: VNodeChild | JSX.Element;
  /**
   * 抽屉容器的类名
   * @type string
   */
  wrapClassName?: string;
  // 类名
  class?: string;
  /**
   * 包含遮罩层的包装元素的样式，与 drawerStyle 对比
   * @type object
   */
  wrapStyle?: CSSProperties;

  /**
   * 弹出层元素的样式
   * @type object
   */
  drawerStyle?: CSSProperties;

  /**
   * 浮动层的样式，通常用于调整其位置
   * @type object
   */
  bodyStyle?: CSSProperties;
  // 头部样式
  headerStyle?: CSSProperties;

  /**
   * 抽屉的宽度
   * @default 256
   * @type string | number
   */
  width?: string | number;

  /**
   * 当抽屉位置为顶部或底部时，抽屉的高度
   * @type string | number
   */
  height?: string | number;

  /**
   * 抽屉的 z-index 值
   * @default 1000
   * @type number
   */
  zIndex?: number;

  /**
   * 抽屉的位置
   * @default 'right'
   * @type string
   */
  placement?: "top" | "right" | "bottom" | "left";
  // 可见性状态改变后的回调函数
  afterVisibleChange?: (visible?: boolean) => void;
  // 是否支持键盘操作
  keyboard?: boolean;
  /**
   * 当用户点击遮罩层、关闭按钮或取消按钮时调用的回调函数
   */
  onClose?: (e?: Event) => void;
}
export interface DrawerActionType {
  scrollBottom: () => void;
  scrollTo: (to: number) => void;
  getScrollWrap: () => Element | null;
}

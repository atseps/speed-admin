import type { VNode } from "vue";
import type { FormItemProps } from "ant-design-vue";

type Component =
  | "Input"
  | "InputGroup"
  | "InputSearch"
  | "TextArea"
  | "InputNumber"
  | "AutoComplete"
  | "Select"
  | "TreeSelect"
  | "Switch"
  | "RadioGroup"
  | "Checkbox"
  | "Cascader"
  | "Slider"
  | "Rate"
  | "DatePicker"
  | "MonthPicker"
  | "RangePicker"
  | "WeekPicker"
  | "TimePicker"
  | "Divider"
  | "CompactSelect"
  | "TableSelect";

export interface SearchFormType {
  dataIndex: string;
  title: string;
  component?: Component | (string & {});
  itemProps?: FormItemProps;
  props?: Recordable;
  slot?: string;
  render?: (
    val: any,
    data: Recordable
  ) => VNode | undefined | JSX.Element | Element | string | number;
}

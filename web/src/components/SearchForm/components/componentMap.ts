import type { Component } from "vue";

/**
 * Component list, register here to setting it in the form
 */
import {
  Input,
  Select,
  Radio,
  Checkbox,
  AutoComplete,
  Cascader,
  DatePicker,
  InputNumber,
  Switch,
  TimePicker,
  TreeSelect,
  Slider,
  Rate,
  Divider
} from "ant-design-vue";
import CompactSelect from "@/components/CompactSelect";
import TableSelect from "@/components/TableSelect";

const componentMap = new Map<string, Component>();

componentMap.set("Input", Input);
componentMap.set("InputGroup", Input.Group);
componentMap.set("InputSearch", Input.Search);
componentMap.set("Textarea", Input.TextArea);
componentMap.set("InputNumber", InputNumber);
componentMap.set("AutoComplete", AutoComplete);
componentMap.set("Select", Select);
componentMap.set("TreeSelect", TreeSelect);
componentMap.set("Switch", Switch);
componentMap.set("RadioGroup", Radio.Group);
componentMap.set("Checkbox", Checkbox);
componentMap.set("CheckboxGroup", Checkbox.Group);
componentMap.set("Cascader", Cascader);
componentMap.set("Slider", Slider);
componentMap.set("Rate", Rate);
componentMap.set("DatePicker", DatePicker);
componentMap.set("MonthPicker", DatePicker.MonthPicker);
componentMap.set("RangePicker", DatePicker.RangePicker);
componentMap.set("WeekPicker", DatePicker.WeekPicker);
componentMap.set("TimePicker", TimePicker);
componentMap.set("Divider", Divider);
componentMap.set("CompactSelect", CompactSelect);
componentMap.set("TableSelect", TableSelect);

export function add(compName: string, component: Component) {
  componentMap.set(compName, component);
}

export function del(compName: string) {
  componentMap.delete(compName);
}

export { componentMap };

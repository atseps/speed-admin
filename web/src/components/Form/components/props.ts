import { propTypes } from "@/utils/propTypes";
export const apiProps = {
  modelValue: [Array, Object, String, Number],
  numberToString: propTypes.bool,
  api: {
    type: Function as PropType<(arg?: Recordable) => Promise<ResponseBody>>,
    default: null
  },
  // api params
  params: {
    type: Object as PropType<Recordable>,
    default: () => ({})
  },
  dictType: propTypes.string.def(""),
  resultField: propTypes.string.def("data"),
  labelField: propTypes.string.def("label"),
  valueField: propTypes.string.def("value"),
  immediate: propTypes.bool.def(true), //是否初始化加载
  alwaysLoad: propTypes.bool.def(false) //每次下拉都加载数据
}

export const apiTreeSelectProps = Object.assign(
  {},
  {
    modelValue: [Array, Object, String, Number],
    api: { type: Function as PropType<(arg?: Recordable) => Promise<ResponseBody>> },
    params: { type: Object },
    immediate: { type: Boolean, default: true },
    resultField: propTypes.string.def("data"),
    labelField: propTypes.string.def("label"),
    valueField: propTypes.string.def("value"),
    alwaysLoad: propTypes.bool.def(false) //每次下拉都加载数据
  }
);

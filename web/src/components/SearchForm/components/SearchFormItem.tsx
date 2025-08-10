import { componentMap } from "./componentMap";
import { getSlot } from "@/utils/helper/tsxHelper";
import { Form } from "ant-design-vue";
import dayjs from "dayjs";

const SearchFormItem = defineComponent({
  inheritAttrs: false,
  props: {
    dataIndex: {
      type: String,
      required: false
    },
    component: {
      type: String,
      default: "Input"
    },
    title: {
      type: String,
      required: false
    },
    slot: {
      type: String
    },
    render: {
      type: Function
    },
    //表单项的参数
    itemProps: {
      type: Object,
      default: () => ({})
    },
    //所渲染组件的参数
    props: {
      type: Object,
      default: () => ({})
    },
    show: {
      type: Boolean,
      default: true
    },
    model: {
      type: Object,
      required: true
    },
    hidden: {
      type: Boolean,
      default: false
    }
  },
  setup(props, { slots }) {
    //渲染表单项
    const renderItem = () => {
      const { slot, render, dataIndex, title, model, itemProps, hidden } = props;
      const scope = { dataIndex, title, data: model };
      const getContent = () => {
        return slot ? getSlot(slots, slot, scope) : render ? render(scope) : renderComponent();
      };

      return (
        <Form.Item hidden={hidden} {...(itemProps as Recordable)} label={title}>
          {() => getContent()}
        </Form.Item>
      );
    };

    const presets = [
      {
        label: "今天",
        value: [dayjs(), dayjs()]
      },
      {
        label: "本周",
        value: [dayjs().startOf("week").add(1, "day"), dayjs().endOf("week").add(1, "day")]
      },
      {
        label: "上周",
        value: [
          dayjs().add(-1, "week").startOf("week").add(1, "day"),
          dayjs().add(-1, "week").endOf("week").add(1, "day")
        ]
      },
      {
        label: "本月",
        value: [dayjs().startOf("month"), dayjs().endOf("month")]
      },
      {
        label: "上月",
        value: [dayjs().add(-1, "month").startOf("month"), dayjs().add(-1, "month").endOf("month")]
      },
      {
        label: "今年",
        value: [dayjs().startOf("year"), dayjs().endOf("year")]
      }
    ];

    //渲染组件
    const renderComponent = () => {
      const { component, dataIndex, model } = props;
      const Comp = componentMap.get(component) as any;
      const propsData: Recordable = {
        allowClear: true,
        getPopupContainer: (trigger: Element) => trigger.parentNode,
        ...props.props
      };
      if (component == "RangePicker") {
        propsData.presets = presets;
      }
      if (component == "Select") {
        propsData.placeholder = propsData?.placeholder || "请选择";
      }
      if (component == "CompactSelect") {
        return <Comp presets={presets} {...props.props} model={model}></Comp>;
      }
      return <Comp {...propsData} v-model:value={model[dataIndex as string]}></Comp>;
    };

    return () => {
      const { component, show } = props;
      if (!componentMap.has(component)) {
        return null;
      }
      return show ? renderItem() : null;
    };
  }
});

export default SearchFormItem;

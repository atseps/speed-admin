<script lang="tsx">
import type { DescriptionProps, DescInstance, DescItem } from "./typing";
import type { DescriptionsProps } from "ant-design-vue/es/descriptions/index";
import type { CSSProperties } from "vue";
import { get, omit } from "lodash-es";
import { Descriptions } from "ant-design-vue";
import { isFunction } from "@/utils/is";
import { getSlot } from "@/utils/helper/tsxHelper";
import SCollapse from "@/components/Collapse";
const props = {
  useCollapse: { type: Boolean, default: false },
  canExpand: { type: Boolean, default: false },
  title: { type: String, default: "" },
  size: {
    type: String,
    validator: (v: string) => ["small", "default", "middle", undefined].includes(v),
    default: "small"
  },
  bordered: { type: Boolean, default: true },
  column: {
    type: [Number, Object] as PropType<number | Recordable>,
    default: () => {
      return { xxl: 4, xl: 3, lg: 3, md: 3, sm: 2, xs: 1 };
    }
  },
  schema: {
    type: Array as PropType<DescItem[]>,
    default: () => []
  },
  data: { type: Object }
};

export default defineComponent({
  props,
  emits: ["register"],
  setup(props, { slots, emit }) {
    const propsRef = ref<Partial<DescriptionProps> | null>(null);

    const attrs = useAttrs();

    const getMergeProps = computed(() => {
      return {
        ...props,
        ...(unref(propsRef) as Recordable)
      } as DescriptionProps;
    });

    const getProps = computed(() => {
      const opt = {
        ...unref(getMergeProps),
        title: props.useCollapse ? undefined : unref(getMergeProps).title
      };
      return opt as DescriptionProps;
    });

    const getDescriptionsProps = computed(() => {
      return { ...unref(attrs), ...unref(getProps) } as DescriptionsProps;
    });

    /**
     * @description:设置desc
     */
    function setDescProps(descProps: Partial<DescriptionProps>): void {
      // Keep the last setDrawerProps
      propsRef.value = { ...(unref(propsRef) as Recordable), ...descProps } as Recordable;
    }

    // Prevent line breaks
    function renderLabel({ label, labelMinWidth, labelStyle }: DescItem) {
      if (!labelStyle && !labelMinWidth) {
        return label;
      }

      const labelStyles: CSSProperties = {
        ...labelStyle,
        minWidth: `${labelMinWidth}px `
      };
      return <div style={labelStyles}>{label}</div>;
    }

    function renderItem() {
      const { schema, data } = unref(getProps);
      return unref(schema)
        .map(item => {
          const { render, field, span, show, contentMinWidth, slot } = item;

          if (show && isFunction(show) && !show(data)) {
            return null;
          }

          const getContent = () => {
            const _data = unref(getProps)?.data;
            if (!_data) {
              return null;
            }
            const getField = get(_data, field);
            if (getField && !_data.hasOwnProperty(field)) {
              return isFunction(render) ? render("", _data) : "";
            }
            return slot
              ? getSlot(slots, slot, { value: getField, data: _data })
              : isFunction(render)
              ? render(getField, _data)
              : getField ?? "";
          };

          const width = contentMinWidth;
          return (
            <Descriptions.Item label={renderLabel(item)} key={field} span={span}>
              {() => {
                if (!contentMinWidth) {
                  return getContent();
                }
                const style: CSSProperties = {
                  minWidth: `${width}px`
                };
                return <div style={style}>{getContent()}</div>;
              }}
            </Descriptions.Item>
          );
        })
        .filter(item => !!item);
    }

    const renderDesc = () => {
      const exclude = props.useCollapse ? ["class", "style"] : ["schema", "data", "canExpand", "useCollapse"];
      const descProps = omit(unref(getDescriptionsProps), exclude);
      return (
        <Descriptions class="BasicDescription" {...descProps}>
          {renderItem()}
        </Descriptions>
      );
    };

    const renderContainer = () => {
      const content = renderDesc();
      const { title, canExpand } = unref(getMergeProps);
      return (
        <SCollapse title={title} canExpand={canExpand}>
          {{ default: () => content }}
        </SCollapse>
      );
    };

    const methods: DescInstance = {
      setDescProps
    };

    emit("register", methods);
    return () => (props.useCollapse ? renderContainer() : renderDesc());
  }
});
</script>

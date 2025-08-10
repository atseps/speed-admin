<script lang="tsx">
import Tooltip from "ant-design-vue/es/tooltip";
import { getStrFullLength, cutStrByFullLength } from "./helper";
import type { VNode } from "vue";
export default defineComponent({
  components: {
    Tooltip
  },
  props: {
    tooltip: {
      type: Boolean,
      default: true
    },
    length: {
      type: Number,
      required: true
    }
  },
  setup(props, { slots }) {
    const getStrDom = (str: string, fullLength: number) => {
      return (
        <span>
          {cutStrByFullLength(str, props.length) + (fullLength > props.length ? "..." : "")}
        </span>
      );
    };

    const getTooltip = (fullStr: string, fullLength: number) => {
      return (
        <Tooltip>
          {{
            title: () => fullStr,
            default: () => getStrDom(fullStr, fullLength)
          }}
        </Tooltip>
      );
    };

    return () => {
      const { tooltip, length } = toRefs(props);
      const str =
        (slots.default &&
          slots
            .default()
            ?.map((vNode: VNode) => vNode.children)
            .join("")
            .trim()) ||
        "";
      const fullLength = getStrFullLength(str);
      const strDom =
        tooltip.value && fullLength > length.value
          ? getTooltip(str, fullLength)
          : getStrDom(str, fullLength);
      return strDom;
    };
  }
});
</script>

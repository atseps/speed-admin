<script lang="tsx">
import SButton from "./SButton.vue";
import { Popconfirm } from "ant-design-vue";
import { extendSlots } from "@/utils/helper/tsxHelper";
import { omit } from "lodash-es";
export default defineComponent({
  inheritAttrs: false,
  props: {
    enable: {
      type: Boolean,
      default: true
    },
    type: {
      type: String,
      default: "link"
    },
    label: {
      type: String,
      default: ""
    }
  },
  setup(props, { slots, attrs }) {

    const getBindValues = computed(() => {
      return Object.assign(
        {
          okText: "确定",
          cancelText: "取消"
        },
        { ...props, ...unref(attrs) }
      );
    });

    return () => {
      const bindValues = omit(unref(getBindValues), "icon");
      const btnBind:Recordable = omit(unref(getBindValues), "title");
      if (btnBind.disabled) btnBind.color = "";
      btnBind.class = "confirm-button";
      const Button = h(
        SButton,
        btnBind,
        props.label ? { default: () => <a>{props.label}</a> } : extendSlots(slots)
      );

      // 如果未启用，则为正常按钮
      if (!props.enable) {
        return Button;
      }
      return <Popconfirm {...bindValues}>{{ default: () => Button }}</Popconfirm>;
    };
  }
});
</script>
<style lang="less" scoped>
.confirm-button {
  padding: 0;
}
</style>

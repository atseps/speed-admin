import { Modal } from "ant-design-vue";
import { defineComponent, toRefs, unref } from "vue";
import { basicProps } from "../props";
import { useModalDragMove } from "../hooks/useModalDrag";
import { extendSlots } from "@/utils/helper/tsxHelper";
import { omit } from "lodash-es";

export default defineComponent({
  name: "Modal",
  inheritAttrs: false,
  props: basicProps,
  emits: ["cancel"],
  setup(props, { slots, emit, attrs }) {
    const { visible, draggable, destroyOnClose } = toRefs(props);

    useModalDragMove({
      visible,
      destroyOnClose,
      draggable
    });

    const onCancel = (e: Event) => {
      emit("cancel", e);
    };

    return () => {
      const propsData = omit({ ...unref(attrs), ...props, onCancel, open: unref(visible) }, [
        "visible"
      ]) as Recordable;
      return <Modal {...propsData}>{extendSlots(slots)}</Modal>;
    };
  }
});

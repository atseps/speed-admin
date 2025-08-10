import { defineComponent } from "vue";
import { Button, Space } from "ant-design-vue";
import type { SubmitterProps } from "./types";

const Submitter = defineComponent<
  SubmitterProps & {
    render?:
      | ((
          props: SubmitterProps & {
            submit: () => void;
            reset: () => void;
          },
          dom: any
        ) => VueNode)
      | false;
  }
>({
  slots: ["submitIcon", "resetIcon"],
  props: [
    "onSubmit",
    "onReset",
    "searchConfig",
    "submitButtonProps",
    "resetButtonProps",
    "render"
  ] as any,
  setup(props, { slots }) {
    return () => {
      const { onSubmit, onReset } = props;

      const { submitText = "提交", resetText = "重置" } = props.searchConfig || {};

      const submit = () => {
        onSubmit?.();
      };
      const reset = () => {
        onReset?.();
      };

      const doms: VueNode = [];
      if (props.submitButtonProps !== false) {
        doms.push(
          <Button
            type={"primary"}
            key="submit"
            {...props.submitButtonProps}
            icon={props.submitButtonProps?.icon || slots.submitIcon?.()}
            onClick={e => {
              if (!(props.resetButtonProps as any)?.preventDefault) {
                submit();
              }
              (props.submitButtonProps as any)?.onClick?.(e);
            }}
          >
            {submitText}
          </Button>
        );
      }

      if (props.resetButtonProps !== false) {
        doms.push(
          <Button
            key="reset"
            {...props.resetButtonProps}
            icon={props.resetButtonProps?.icon || slots.resetIcon?.()}
            onClick={e => {
              if (!(props.resetButtonProps as any)?.preventDefault) {
                reset();
              }
              (props.resetButtonProps as any)?.onClick?.(e);
            }}
          >
            {resetText}
          </Button>
        );
      }
      const renderDom = props.render ? props.render({ ...props, submit, reset }, doms) : doms;
      if (!renderDom) {
        return null;
      }
      if (Array.isArray(renderDom)) {
        if (renderDom.length === 0) {
          return undefined;
        }
        if (renderDom.length === 1) {
          return renderDom[0];
        }
        return <Space wrap>{renderDom}</Space>;
      }
      return renderDom;
    };
  }
});

export { Submitter };

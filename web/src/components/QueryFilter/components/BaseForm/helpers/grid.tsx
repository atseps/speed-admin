import { computed, FunctionalComponent, ref, ExtractPropTypes } from "vue";
import { Row, Col, type RowProps, ColProps } from "ant-design-vue";
export const proFormGridConfig = {
  grid: {
    type: Boolean as PropType<boolean>
  },
  /**
   * @default
   * { xs: 24 }
   */
  colProps: {
    type: Object as PropType<ColProps>
  },
  /**
   * @default
   * { gutter: 8 }
   */
  rowProps: {
    type: Object as PropType<RowProps>
  }
};

export type ProFormGridConfig = Partial<ExtractPropTypes<typeof proFormGridConfig>>;

export interface CommonProps {
  Wrapper?: FunctionalComponent<any>;
}

export interface GridHelpers {
  RowWrapper: FunctionalComponent<RowProps & CommonProps>;
  ColWrapper: FunctionalComponent<ColProps & CommonProps>;
  grid: boolean;
}

export const gridHelpers: (config: ProFormGridConfig & CommonProps) => GridHelpers = ({
  grid,
  rowProps,
  colProps
}) => ({
  grid: !!grid,
  RowWrapper({ Wrapper, ...props }, { slots }) {
    const children = slots?.default?.();
    if (!grid) {
      return Wrapper ? <Wrapper>{children}</Wrapper> : (children as any);
    }
    return (
      <Row gutter={8} {...rowProps} {...props}>
        {children}
      </Row>
    );
  },
  ColWrapper({ Wrapper, ...rest }, { slots }) {
    const children = slots?.default?.();
    const props = computed(() => {
      const originProps = { ...colProps, ...rest };

      /**
       * `xs` takes precedence over `span`
       * avoid `span` doesn't work
       */
      if (typeof originProps.span === "undefined" && typeof originProps.xs === "undefined") {
        originProps.xs = 24;
      }

      return originProps;
    });

    if (!grid) {
      return Wrapper ? <Wrapper>{children}</Wrapper> : children;
    }
    return (<Col {...props.value}>{children}</Col>) as any;
  }
});

export const useGridHelpers = (props?: (ProFormGridConfig & CommonProps) | boolean) => {
  const _grid = ref<boolean>();
  const _colProps = ref<ColProps>();

  const config = computed(() => {
    if (typeof props === "object") {
      return props;
    }
    // boolean
    return {
      grid: props
    };
  });

  return computed(() => {
    return gridHelpers({
      grid: !!(_grid.value || config.value.grid),
      rowProps: config.value?.rowProps,
      colProps: config.value?.colProps || _colProps.value,
      Wrapper: config.value?.Wrapper
    });
  });
};

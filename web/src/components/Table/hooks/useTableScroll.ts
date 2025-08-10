import type { Ref, ComputedRef } from "vue";
import { getViewportOffset ,getElementHeightWithMargin} from "@/utils/domUtils";
import { isBoolean } from "@/utils/is";
import { useDebounceFn } from "@vueuse/core";
import { useWindowSizeFn } from "@/hooks/useWindowSizeFn";
import { onMountedOrActivated } from "@/hooks/core/onMountedOrActivated";
import { useModalContext } from "@/components/Modal";
import { useSetting } from "@/layout/hooks";
import type { BasicTableProps, TableColumnProps } from "../types";
interface ComponentElRef<T extends HTMLElement = HTMLDivElement> {
  $el: T;
}
export function useTableScroll(
  props: ComputedRef<BasicTableProps>,
  tableElRef: Ref<ComponentElRef>,
  tableDataRef: Ref<Recordable[]>,
  getColumns: ComputedRef<TableColumnProps[]>
) {
  //表格的高度
  const tableHeight: Ref<Nullable<number | string>> = ref(167);
  const modalFn = useModalContext();
  const { showMultiTabs } = useSetting();

  const getCanResize = computed(() => {
    const { canResize, scroll } = unref(props);
    return canResize && !(scroll || {}).y;
  });

  // Greater than animation time 280
  const debounceRedoHeight = useDebounceFn(redoHeight, 100);

  watch(
    () => [unref(getCanResize), unref(tableDataRef)?.length, unref(showMultiTabs)],
    () => {
      if (unref(props).debounceRender) {
        debounceRedoHeight();
      } else {
        calcTableHeight();
      }
    },
    {
      flush: "post"
    }
  );

  function redoHeight() {
    nextTick(() => {
      calcTableHeight();
    });
  }
  //设置表格高度
  const setHeight = (heigh: number) => {
    tableHeight.value = heigh;
    modalFn?.redoModalHeight?.();
  };

  // No need to repeat queries
  let paginationEl: HTMLElement | null;
  let footerEl: HTMLElement | null;
  let bodyEl: HTMLElement | null;
  /**
   * table wrapper padding 的高度
   * @description 来自于 .s-table-wrapper
   */
  const tableWrapperPadding = 33;

  //处理滚动条
  function handleScrollBar(bodyEl: HTMLElement, tableEl: Element) {
    const hasScrollBarY = bodyEl.scrollHeight > bodyEl.clientHeight;
    const hasScrollBarX = bodyEl.scrollWidth > bodyEl.clientWidth;

    if (hasScrollBarY) {
      tableEl.classList.contains('hide-scrollbar-y') &&
        tableEl.classList.remove('hide-scrollbar-y');
    } else {
      !tableEl.classList.contains('hide-scrollbar-y') && tableEl.classList.add('hide-scrollbar-y');
    }

    if (hasScrollBarX) {
      tableEl.classList.contains('hide-scrollbar-x') &&
        tableEl.classList.remove('hide-scrollbar-x');
    } else {
      !tableEl.classList.contains('hide-scrollbar-x') && tableEl.classList.add('hide-scrollbar-x');
    }
  }


  //计算表头的高度
  function calcHeaderHeight(headEl: Element): number {
    let headerHeight = 0;
    if (headEl) {
      headerHeight = (headEl as HTMLElement).offsetHeight;
    }
    return headerHeight;
  }

  //计算布局页脚的高度
  function calcLayoutFooterHeight() {
    const footerElement = document.querySelector(".layout-footer") as HTMLElement;
    const footerHeight = getElementHeightWithMargin(footerElement);
    return footerHeight;
  }


  /**
   * 计算分页器高度
   * @param tableEl table element
   * @returns number
   */
  function caclPaginationHeight(tableEl: Element): number {
    let paginationHeight = 0;
    paginationEl = tableEl.querySelector(".ant-pagination") as HTMLElement;
    if (paginationEl) {
      paginationHeight += paginationEl.offsetHeight || 0;
    } else {
      paginationHeight = -13;
    }
    return paginationHeight
  }

  //计算表格高度
  const calcTableHeight = async () => {
    const { resizeHeightOffset, pagination, maxHeight, debounceRender } = unref(props);

    //表格数据
    const tableData = unref(tableDataRef);

    const table = unref(tableElRef);
    if (!table) return;

    const tableEl: Element = table?.$el;
    if (!tableEl) return;

    if (!bodyEl) {
      bodyEl = tableEl.querySelector(".ant-table-body");
      if (!bodyEl) return;
    }

    handleScrollBar(bodyEl, tableEl)

    bodyEl!.style.height = "unset";

    if (!unref(getCanResize) || !unref(tableData) || (debounceRender && tableData.length === 0))
      return;

    await nextTick();

    const headEl = tableEl.querySelector(".ant-table-thead");
    if (!headEl) return;

    //表格顶部到可视窗口底部的距离
    const { bottomIncludeBody } = getViewportOffset(headEl);

    //布局页脚高度
    const layoutFooterHeight = calcLayoutFooterHeight();

    const paddingHeight = tableWrapperPadding + layoutFooterHeight;

    // 分页的高度
    const paginationHeight = caclPaginationHeight(tableEl)

    //表尾的高度
    let footerHeight = 0;
    if (!isBoolean(pagination)) {
      footerEl = tableEl.querySelector(".ant-table-footer") as HTMLElement;
      if (footerEl) {
        footerHeight += footerEl.offsetHeight || 0;
      }
    }

    //表格合计的高度
    const summaryEl = tableEl.querySelector(".ant-table-summary") as HTMLElement;
    if (summaryEl) {
      footerHeight += summaryEl.offsetHeight || 0;
    }

    //表头的高度
    const headerHeight = calcHeaderHeight(headEl)

    let height = Math.floor(
      bottomIncludeBody -
        (resizeHeightOffset || 0) -
        paddingHeight -
        paginationHeight -
        footerHeight -
        headerHeight
    );

    height = (height > maxHeight! ? (maxHeight as number) : height) ?? height;
    setHeight(height);

    bodyEl!.style.height = `${height}px`;
    if (tableData.length === 0) {
      const emptyDataEl = tableEl.querySelector(".ant-table-expanded-row-fixed") as HTMLElement;
      if (emptyDataEl && emptyDataEl.style) {
        emptyDataEl.style.height = `${height - 9}px`;
      }
    }
  };

  const getScroll = computed(() => {
    const { canResize, scroll } = unref(props);

    let width = 0;

    unref(getColumns).forEach(item => {
      if (item.width) width += Number.parseFloat(item.width as string);
    });

    const table = unref(tableElRef);
    const tableWidth = table?.$el?.offsetWidth ?? 600; // 默认宽度不小于，列中指定的宽度总合
    const canScrollX = tableWidth == 0 || width == 0 || tableWidth > width;

    return {
      x: canScrollX ? (canResize ? width : undefined) : width,
      y: canResize ? unref(tableHeight) : null,
      scrollToFirstRowOnChange: true,
      ...scroll
    };
  });

  useWindowSizeFn(calcTableHeight, 280);

  onMountedOrActivated(() => {
    calcTableHeight();
    nextTick(() => {
      debounceRedoHeight();
    });
  });

  return {
    getScroll,
    redoHeight
  };
}

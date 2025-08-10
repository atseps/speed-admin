import { nextTick, unref } from "vue";
import type { Ref } from "vue";
import type { Options } from "sortablejs";
import Sortable from "sortablejs";

export function useSortable(el: HTMLElement | Ref<HTMLElement>, options?: Options) {
  nextTick(() => {
    if (!el) return;
    Sortable.create(unref(el), {
      animation: 500,
      ...options
    });
  });
}

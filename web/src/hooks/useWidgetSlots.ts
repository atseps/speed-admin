import { useSlots } from "vue";
import type { Slots } from "vue";
import { pick } from "lodash-es";

export function useWidgetSlots(slotNames: string[] = []) {
  const slots = useSlots() as Slots;

  // 获取需要的插槽
  const customSlots = Object.keys(pick(slots, [...slotNames]));

  // 获取所有可用的插槽
  const componentSlots = Object.keys(slots);

  return {
    customSlots,
    componentSlots,
    slots
  };
}

import { h, type VNode } from "vue";
import { isString } from 'lodash-es';
import Icon from "@/components/Icon";
export const TreeIcon = ({ icon }: { icon: VNode | string | undefined }) => {
  if (!icon) return null;
  if (isString(icon)) {
    return h(Icon, { type: icon, class: "mr-1" });
  }
  return h(icon);
};

import type { GlobalToken } from "ant-design-vue/es/theme";
import { kebabCase } from "lodash";
import { updateCSS } from "ant-design-vue/lib/vc-util/Dom/dynamicCSS";
import canUseDom from "ant-design-vue/lib/_util/canUseDom";
import { isNumber } from "@/utils/is";

const formatKey = (key: string, prefixCls: string) => {
  return `${prefixCls}${kebabCase(key)}`;
};
const prefixCls = "--ant-";

const dynamicStyleMark = `${prefixCls}${Date.now()}-${Math.random()}`;

const colorKey = [
  "colorPrimary", //主色调
  "colorPrimaryBg", //色调的浅色背景
  "colorBgContainer", //组件的容器颜色
  "colorBgLayout", //布局背景色
  "colorBorder", //边框颜色
  "borderRadius", //边框圆角
  "colorBorderSecondary", //比默认边框色要浅一级
  "colorText", //默认的文本颜色
  "colorTextSecondary", //第二梯度的文本色，一般用在不那么需要强化文本颜色的场景，例如 Label 文本、Menu 的文本选中态等场景。
  "colorTextTertiary", //第三级文本色一般用于描述性文本，例如表单的中的补充说明文本、列表的描述性文本等场景。
  "colorTextQuaternary" //第四级文本色是最浅的文本色，例如表单的输入提示文本、禁用色文本等。
];

export const registerTokenToCSSVar = (token: GlobalToken) => {
  const variables: Record<string, any> = {};
  if (!token) return;
  for (const key in token) {
    const val = token[key as keyof GlobalToken];
    if (colorKey.includes(key)) {
      variables[formatKey(key, prefixCls)] = isNumber(val) ? `${val}px` : val;
    }
  }
  const cssList = Object.keys(variables).map(key => `${key}: ${variables[key]};`);
  if (canUseDom()) {
    updateCSS(`:root {${cssList.join("\n")}}`, `${dynamicStyleMark}-dynamic-theme`);
  }
};

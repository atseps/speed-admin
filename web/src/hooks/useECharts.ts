import { computed } from "vue";
import { EChartsOption } from "echarts";
import { useAppStore } from "@/store/modules/app";

interface optionsFn {
  (isDark: boolean): EChartsOption;
}

export default function useChartOption(sourceOption: optionsFn) {
  const appStore = useAppStore();
  const isDark = computed(() => {
    return appStore.theme === "realDark";
  });
  const chartOption = computed<EChartsOption>(() => {
    return sourceOption(isDark.value);
  });
  return {
    chartOption
  };
}

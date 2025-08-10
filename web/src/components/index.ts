import { App } from "vue";
import { PageView } from "@/layout";
import { use } from "echarts/core";
import { CanvasRenderer } from "echarts/renderers";
import { BarChart, LineChart, PieChart, RadarChart } from "echarts/charts";
import {
  GridComponent,
  TooltipComponent,
  LegendComponent,
  DataZoomComponent,
  GraphicComponent,
  ToolboxComponent,
  TitleComponent
} from "echarts/components";

export default function setupComponent(app: App) {
  use([
    TitleComponent,
    CanvasRenderer,
    BarChart,
    LineChart,
    PieChart,
    RadarChart,
    GridComponent,
    TooltipComponent,
    LegendComponent,
    DataZoomComponent,
    GraphicComponent,
    ToolboxComponent
  ]);
  app.component("PageView", PageView);
}

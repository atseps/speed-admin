import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import { resolve } from "path";
import vueJsx from "@vitejs/plugin-vue-jsx";
import { createSvgIconsPlugin } from "vite-plugin-svg-icons";
import path from "path";

//按需导入
import Components from "unplugin-vue-components/vite";
import { AntDesignVueResolver } from "unplugin-vue-components/resolvers";
import AutoImport from "unplugin-auto-import/vite";
function getAntdDeps() {
  const prefixLib = "ant-design-vue/lib/";
  const prefixEs = "ant-design-vue/es/";
  const libModules = [
    "form",
    "select",
    "checkbox",
    "input-number",
    "Radio/Group",
    "input/inputProps",
  ];
  const esModules = ["vc-resize-observer", "_util/vnode", "_util/props-util"];
  return [...libModules.map(m => prefixLib + m), ...esModules.map(m => prefixEs + m)];
}
// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd());
  return {
    base: env.VITE_APP_BASE_URL,
    plugins: [
      vue(),
      vueJsx(),
      AutoImport({
        resolvers: [AntDesignVueResolver()],
        imports: [
          "vue",
          "vue-router",
          {
            "@/utils/globalFunctions": [
              "useTable",
              "useModal",
              "useDrawer",
              "useDrawerInner",
              "useModalInner"
            ]
          }
        ],
        dirs: ["src/hooks/web"],
        dts: "types/auto-imports.d.ts"
      }),
      Components({
        dts: "types/components.d.ts", //ts支持
        dirs: ["src/components"], //按需加载全局组件
        resolvers: [
          AntDesignVueResolver({
            importStyle: false
          })
        ]
      }),
      createSvgIconsPlugin({
        // 指定需要缓存的图标文件夹
        iconDirs: [path.resolve(process.cwd(), "src/assets/svgs")],
        // 指定symbolId格式
        symbolId: "icon-[dir]-[name]"
      })
    ],
    server: {
      open: true,
      host: true
    },
    resolve: {
      //配置别名
      alias: {
        "@": resolve(__dirname, "src") // 设置 `@` 指向 `src` 目录
      }
    },
    // 生产环境打包配置
    //去除 console debugger
    build: {
      minify: "terser",
      terserOptions: {
        compress: {
          drop_console: true,
          drop_debugger: true
        }
      }
    },
    optimizeDeps: {
      include: [
        "vue",
        "@vueuse/core",
        "vue-router",
        "lodash-es",
        "vue-echarts",
        ...getAntdDeps()
      ]
    },
    css: {
      preprocessorOptions: {
        less: {
          javascriptEnabled: true
        }
      }
    }
  };
});

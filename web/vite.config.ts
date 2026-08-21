import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import { resolve } from "path";
import vueJsx from "@vitejs/plugin-vue-jsx";
import { createSvgIconsPlugin } from "vite-plugin-svg-icons-ng";

// 按需导入
import Components from "unplugin-vue-components/vite";
import { AntDesignVueResolver } from "unplugin-vue-components/resolvers";
import AutoImport from "unplugin-auto-import/vite";

/**
 * Ant Design Vue 依赖预构建入口集合
 */
function getAntdDeps() {
  return [
    "ant-design-vue/es/form",
    "ant-design-vue/es/select",
    "ant-design-vue/es/checkbox",
    "ant-design-vue/es/input-number",
    "ant-design-vue/es/radio/Group",
    "ant-design-vue/es/input/inputProps",
    "ant-design-vue/es/button/buttonTypes",
    "ant-design-vue/es/vc-resize-observer",
    "ant-design-vue/es/_util/vnode",
    "ant-design-vue/es/date-picker/dayjs",
    "ant-design-vue/es/_util/props-util"
  ];
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
        iconDirs: [resolve(process.cwd(), "src/assets/svgs")],
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
        "@": resolve(import.meta.dirname, "src") // 设置 `@` 指向 `src` 目录
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
        "vue-router",
        "lodash-es",
        "vue-echarts",
        "@wangeditor/editor-for-vue",
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

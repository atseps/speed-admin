import { createApp } from "vue";
import App from "./App.vue";
import { setupStore } from "@/store";
import router from "@/router";
import { setupRouterGuard } from "@/router/permission";
import setupComponent from "@/components";
import { setupGlobDirectives } from "@/directives";
import "@/styles/global.less";
import "ant-design-vue/dist/reset.css";
import "virtual:svg-icons-register";

import "highlight.js/styles/github.css";
import "highlight.js/lib/common";
import hljsVuePlugin from "@highlightjs/vue-plugin";

const app = createApp(App);

app.use(router);
app.use(hljsVuePlugin);

//注册全局指令
setupGlobDirectives(app);

//注册全局组件
setupComponent(app);

//注册路由守卫
setupRouterGuard(router);

//注册store
setupStore(app);

app.mount("#app");

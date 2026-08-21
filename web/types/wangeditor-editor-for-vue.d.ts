declare module "@wangeditor/editor-for-vue" {
  import type { DefineComponent } from "vue";

  /** wangEditor Vue 3 编辑器组件。 */
  export const Editor: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>;

  /** wangEditor Vue 3 工具栏组件。 */
  export const Toolbar: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>;
}

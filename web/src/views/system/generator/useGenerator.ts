import { makeCode } from "@/api/system/generator";
import { isProdMode } from "@/utils";
export default function useGenerator(showLoading: boolean = true) {
  const generatorCode = (id: string) => {
    const { message } = useMessage();
    const key = "updatable";
    if (isProdMode()) {
      return message.error("生产环境无法使用代码生成！");
    }
    showLoading && message.loading({ content: "正在生成...", key });
    return makeCode(id).then(response => {
      const { data, msg } = response;
      if (response.code == 1) {
        message.success({ content: msg, key, duration: 1 });
        if (data.file) {
          const link = document.createElement("a");
          link.href = data.file;
          link.setAttribute("download", "");
          link.click();
        }
      } else {
        message.error({ content: msg, key, duration: 1 });
      }
      return response;
    });
  }
  return { generatorCode };
}

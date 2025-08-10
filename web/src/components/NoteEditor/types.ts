import type { UploadFile } from "ant-design-vue";

export interface Files<T = any> extends UploadFile<T> {
  id?: string | number;
}
export interface ShowUploadOptions {
  image?: boolean;
  file?: boolean;
}

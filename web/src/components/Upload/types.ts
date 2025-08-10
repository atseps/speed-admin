import type { UploadFile as AUploadFile } from "ant-design-vue";

export enum UploadResultStatus {
  SUCCESS = "success",
  ERROR = "error",
  UPLOADING = "uploading"
}

export interface BaseFileItem {
  uid: string | number;
  url: string;
  name?: string;
}

export interface FileItem {
  thumbUrl?: string;
  name: string;
  size: number;
  type?: string;
  percent: number;
  file: File;
  status?: UploadResultStatus;
  response?: Recordable<any>;
  uid: string;
}

export type ShowType = "button" | "image" | "avatar";

export interface UploadFile<T = any> extends AUploadFile<T> {
  id?: string;
  url?: string;
}

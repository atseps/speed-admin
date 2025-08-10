export enum SizeEnum {
  DEFAULT = "default",
  SMALL = "small",
  LARGE = "large",
  DEFAULF = "default"
}

export interface LoadingProps {
  tip: string;
  size: SizeEnum;
  absolute: boolean;
  loading: boolean;
  background: string;
  theme: "dark" | "light";
}

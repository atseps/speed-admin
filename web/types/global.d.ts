import type { ComponentRenderProxy, VNode, VNodeChild, PropType as VuePropType } from "vue";
declare global {

	declare type PropType<T> = VuePropType<T>;

	declare type VueNode = VNodeChild | JSX.Element;

	declare type Recordable<T = any> = Record<string, T>;

	declare type Nullable<T> = T | null;
  export enum ResultCode {
    SUCCESS = 1,
    FAIL = 0,
    UNAUTHORIZED = 401,
  }
	export interface ResponseBody<T = any> {
		code: ResultCode;
		data: T;
		msg: string;
		time: number;
	}

	declare interface Fn<T = any> {
		(...arg: T[]): T;
	}

	declare interface PromiseFn<T = any, R = T> {
		(...arg: T[]): Promise<R>;
	}

	declare module "vue" {
		export type JSXComponent<Props = any> =
			| { new (): ComponentPublicInstance<Props> }
			| FunctionalComponent<Props>;
	}

	export type DynamicProps<T> = {
		[P in keyof T]: Ref<T[P]> | T[P] | ComputedRef<T[P]>;
	};

	namespace JSX {
		// tslint:disable no-empty-interface
		type Element = VNode;
		// tslint:disable no-empty-interface
		type ElementClass = ComponentRenderProxy;
		interface ElementAttributesProperty {
			$props: any;
		}
		interface IntrinsicElements {
			[elem: string]: any;
		}
		interface IntrinsicAttributes {
			[elem: string]: any;
		}
	}
}

declare module 'axios' {
  export interface AxiosInstance {
    <T = any>(config: AxiosRequestConfig): Promise<ResponseBody<T>>;
    (config: AxiosRequestConfig & { responseType: 'blob' }): Promise<Blob>;

    get<T = any>(url: string, config?: AxiosRequestConfig): Promise<ResponseBody<T>>;
    get(url: string, config: AxiosRequestConfig & { responseType: 'blob' }): Promise<Blob>;

    post<T = any>(url: string, data?: any, config?: AxiosRequestConfig): Promise<ResponseBody<T>>;
    post(url: string, data?: any, config?: AxiosRequestConfig & { responseType: 'blob' }): Promise<Blob>;

    put<T = any>(url: string, data?: any, config?: AxiosRequestConfig): Promise<ResponseBody<T>>;
    delete<T = any>(url: string, config?: AxiosRequestConfig): Promise<ResponseBody<T>>;
  }
}

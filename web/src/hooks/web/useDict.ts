import { isEmpty, isString } from "@/utils/is";
import { getDict as getDictData } from "@/api/common";
import { keys } from "lodash-es";
import type { Reactive } from "vue";

export interface DictRecord {
  options: Reactive<Recordable>;
  request: Promise<any>;
}

export function useDict(dictType: string[] | string, Fn?: Function): DictRecord {
  if (isEmpty(dictType)) {
    return {
      options: reactive({}),
      request: Promise.resolve()
    };
  }
  const options: DictRecord["options"] = reactive({});
  if (isString(dictType)) {
    dictType = [dictType];
  }
  const request = new Promise<DictRecord["options"]>((resolve, reject) => {
    getDictData(dictType)
      .then(({ data }) => {
        keys(data).forEach(key => {
          options[key] = data[key];
        });
        resolve(unref(options));
        Fn && Fn(unref(options));
      })
      .catch(err => reject(err));
  });

  return { options, request };
}

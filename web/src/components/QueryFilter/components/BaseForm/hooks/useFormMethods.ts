import { toRaw, isProxy } from "vue";
import { cloneDeep } from "lodash-es";

export const proxyToRaw = (proxy: Recordable) => {
  const target: Recordable = {};
  for (const key in proxy) {
    let value = proxy[key];
    if (isProxy(value)) {
      value = cloneDeep(toRaw(value));
    }
    target[key] = value;
  }
  return target;
};

export const useFormMethods = () => {
  function handleFormValues(values: Recordable) {
    return proxyToRaw(values);
  }
  return {
    handleFormValues
  };
};

export type FormMethods = ReturnType<typeof useFormMethods>;

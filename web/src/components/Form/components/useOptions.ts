import { isFunction, isEmpty } from "@/utils/is";
import { get, omit,isEqual } from "lodash-es";
type OptionsItem = { label: string; value: string; disabled?: boolean };
type Emit = (event: 'options-change', options: OptionsItem[]) => void
type PropsType = {
  api?: (...arg: any[]) => Promise<any>;
  params: Recordable;
  dictType?: string;
  resultField: string;
  labelField: string;
  valueField: string;
  numberToString: boolean;
  immediate: boolean;
  alwaysLoad: boolean;
  options?: any;
}
export default function useOptions(props: PropsType, emit:Emit) {
  const options = ref<OptionsItem[]>([]);
  const loading = ref(false);

  const isFirstLoaded = ref(false); //首次是否加载过了

  if (!isEmpty(props.dictType)) {
    useDict(props.dictType, (data: Recordable) => {
      options.value = data[props.dictType as string];
    });
  }

  const getOptions = computed(() => {
    const { labelField, valueField, numberToString } = props;
    return unref(options)?.reduce((prev, next: Recordable) => {
      if (next) {
        const value = next[valueField];
        prev.push({
          ...omit(next, [labelField, valueField]),
          label: next[labelField],
          value: numberToString ? `${value}` : value
        });
      }
      return prev;
    }, [] as OptionsItem[]);
  });


  watch(
    () => props.options,
    (value) => {
      if (value && value.length > 0) {
        options.value = value;
      }
    },
    {
      immediate: true,
      deep: true
    }
  );

  const fetch = async () => {
    const api = props.api;
    if (!api || !isFunction(api) || !isEmpty(props.dictType)) return;
    options.value = [];
    try {
      loading.value = true;
      const res = await api(props.params);
      isFirstLoaded.value = true;
      if (Array.isArray(res)) {
        options.value = res;
        emitChange();
        return;
      }
      if (props.resultField) {
        options.value = get(res, props.resultField) || [];
        emitChange();
      }
    } catch (error) {
      isFirstLoaded.value = false;
      console.warn(error);
    } finally {
      loading.value = false;
    }
  };

  watch(
    () => props.params,
     (value, oldValue) => {
      if (isEqual(value, oldValue)) return;
      fetch();
    },
    { deep: true,
      immediate: props.immediate
    },
  );

  const handleFetch = async (visible: boolean) => {
    if (visible) {
      if (props.alwaysLoad) {
        await fetch();
      } else if (!props.immediate && !unref(isFirstLoaded)) {
        await fetch();
      }
    }
  };

  const emitChange = () => {
    emit("options-change", unref(getOptions));
  };

  return {
    getOptions,
    handleFetch
  };
}

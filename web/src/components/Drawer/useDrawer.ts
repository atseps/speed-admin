import type {
  UseDrawerReturnType,
  DrawerInstance,
  ReturnMethods,
  DrawerProps,
  UseDrawerInnerReturnType
} from "./typing";

import { isProdMode, getDynamicProps } from "@/utils";
import { isFunction } from "@/utils/is";
import { tryOnUnmounted } from "@vueuse/core";
import { isEqual } from "lodash-es";
import type { WatchStopHandle } from "vue";

const dataTransferRef = reactive<Recordable>({});

const visibleData = reactive<{ [key: number]: boolean }>({});


export function useDrawer(props?: Partial<DrawerProps>): UseDrawerReturnType {
  if (!getCurrentInstance()) {
    console.error("useDrawer() can only be used inside setup() or functional components!");
  }
  const drawer = ref<DrawerInstance | null>(null);
  const loaded = ref<Nullable<boolean>>(false);
  const uid = ref<string>("");
  let stopWatch: WatchStopHandle;

  function register(instance: DrawerInstance, uuid: any) {
    isProdMode() &&
      tryOnUnmounted(() => {
        drawer.value = null;
        loaded.value = null;
        dataTransferRef[unref(uid)] = null;
      });

    if (unref(loaded) && isProdMode() && instance === unref(drawer)) {
      return;
    }
    uid.value = uuid;
    drawer.value = instance;
    loaded.value = true;
    props && instance.setDrawerProps(getDynamicProps(props));
    stopWatch?.();

    stopWatch = watch(
      () => props,
      () => {
        props && instance.setDrawerProps(getDynamicProps(props));
      },
      {
        immediate: true,
        deep: true
      }
    );
    instance.emitVisible = (visible: boolean, uid: number) => {
      visibleData[uid] = visible;
    };
  }

  const getInstance = () => {
    const instance = unref(drawer);
    if (!instance) {
      console.error("useDrawer instance is undefined!");
    }
    return instance;
  };

  const methods: ReturnMethods = {
    setDrawerProps: (props: Partial<DrawerProps>): void => {
      getInstance()?.setDrawerProps(props);
    },

    getVisible: computed((): boolean => {
      return visibleData[~~unref(uid)];
    }),

    openDrawer: <T = any>(visible = true, data?: T, openOnSet = true): void => {
      getInstance()?.setDrawerProps({
        visible: visible
      });
      if (!data) return;

      if (openOnSet) {
        dataTransferRef[unref(uid)] = null;
        dataTransferRef[unref(uid)] = toRaw(data);
        return;
      }
      const equal = isEqual(toRaw(dataTransferRef[unref(uid)]), toRaw(data));
      if (!equal) {
        dataTransferRef[unref(uid)] = toRaw(data);
      }
    },
    closeDrawer: () => {
      getInstance()?.setDrawerProps({ visible: false });
    }
  };

  return [register, methods];
}

export const useDrawerInner = (callbackFn?: Fn): UseDrawerInnerReturnType => {
  const drawerInstanceRef = ref<Nullable<DrawerInstance>>(null);
  const currentInstance = getCurrentInstance();
  const uidRef = ref<string>("");

  if (!getCurrentInstance()) {
    console.error("useDrawerInner() can only be used inside setup() or functional components!");
  }

  const getInstance = () => {
    const instance = unref(drawerInstanceRef);
    if (!instance) {
      console.error("useDrawerInner instance is undefined!");
      return;
    }
    return instance;
  };

  const register = (modalInstance: DrawerInstance, uuid: any) => {
    isProdMode() &&
      tryOnUnmounted(() => {
        drawerInstanceRef.value = null;
      });

    uidRef.value = uuid;
    drawerInstanceRef.value = modalInstance;
    currentInstance?.emit("register", modalInstance, uuid);
  };

  watchEffect(() => {
    const data = dataTransferRef[unref(uidRef)];
    if (!data) return;
    if (!callbackFn || !isFunction(callbackFn)) return;
    nextTick(async () => {
      const instance = getInstance();
      const { request, showLoading } = instance?.props;
      if (request && isFunction(request)) {
        showLoading && instance?.setDrawerProps({ loading: true });
        try {
          const { data: result } = await request(data);
          callbackFn({ data: result, record: data });
        } finally {
          instance?.setDrawerProps({ loading: false });
        }
      } else {
        callbackFn(data);
      }
    });
  });

  return [
    register,
    {
      changeLoading: (loading = true) => {
        getInstance()?.setDrawerProps({ loading });
      },

      changeOkLoading: (loading = true) => {
        getInstance()?.setDrawerProps({ confirmLoading: loading });
      },
      getVisible: computed((): boolean => {
        return visibleData[~~unref(uidRef)];
      }),

      closeDrawer: () => {
        getInstance()?.setDrawerProps({ visible: false });
      },

      setDrawerProps: (props: Partial<DrawerProps>) => {
        getInstance()?.setDrawerProps(props);
      }
    }
  ];
};

<template>
  <Modal class="basic-modal" v-bind="getBindValue" @cancel="handleCancel">
    <template #closeIcon v-if="!$slots.closeIcon">
      <ModalClose
        :canFullscreen="getProps.canFullscreen"
        :fullScreen="fullScreenRef"
        @cancel="handleCancel"
        @fullscreen="handleFullScreen"
      />
    </template>

    <ModalWrapper
      v-if="getProps.useContainer"
      :useWrapper="getProps.useWrapper"
      :footerOffset="wrapperFooterOffset"
      :fullScreen="fullScreenRef"
      ref="modalWrapperRef"
      :loading="getProps.loading"
      :loading-tip="getProps.loadingTip"
      :minHeight="getProps.minHeight"
      :height="getWrapperHeight"
      :visible="visibleRef"
      :centered="getProps.centered"
      :modalFooterHeight="footer !== undefined && !footer ? 0 : undefined"
      v-bind="omit(getProps.wrapperProps, 'visible', 'height', 'modalFooterHeight')"
      @ext-height="handleExtHeight"
      @height-change="handleHeightChange"
    >
      <slot></slot>
    </ModalWrapper>
    <template v-else>
      <slot></slot>
    </template>

    <template #[item]="data" v-for="item in customSlots">
      <slot :name="item" v-bind="data || {}"></slot>
    </template>

    <template #footer v-if="!$slots.footer">
      <ModalFooter v-bind="getBindValue" @ok="handleOk" @cancel="handleCancel">
        <template #[item]="data" v-for="item in componentSlots">
          <slot :name="item" v-bind="data || {}"></slot>
        </template>
      </ModalFooter>
    </template>
  </Modal>
</template>
<script setup lang="ts">
import type { ModalProps, ModalMethods } from "./typing";
import Modal from "./components/Modal";
import ModalWrapper from "./components/ModalWrapper.vue";
import ModalClose from "./components/ModalClose.vue";
import ModalFooter from "./components/ModalFooter.vue";
import { isFunction } from "@/utils/is";
import { deepMerge } from "@/utils";
import { basicProps } from "./props";
import { useFullScreen } from "./hooks/useModalFullScreen";
import { omit } from "lodash-es";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

const props = defineProps(basicProps);
const emit = defineEmits([
  "visible-change",
  "height-change",
  "cancel",
  "ok",
  "register",
  "update:visible"
]);
defineOptions({ inheritAttrs: false });

const visibleRef = ref(false);
const propsRef = ref<Partial<ModalProps> | null>(null);
const modalWrapperRef = ref<any>(null);
const prefixCls = "basic-modal";
const extHeightRef = ref(0);

const { componentSlots, slots } = useWidgetSlots();
const customSlots = Object.keys(omit(slots, "default"));

const modalMethods: ModalMethods = {
  setModalProps,
  emitVisible: undefined,
  redoModalHeight: () => {
    nextTick(() => {
      if (unref(modalWrapperRef)) {
        (unref(modalWrapperRef) as any).setModalHeight();
      }
    });
  }
};

// 注册实例
const instance = getCurrentInstance();
if (instance) {
  emit("register", modalMethods, instance.uid);
}

const getMergeProps = computed((): Recordable => {
  return {
    ...props,
    ...(unref(propsRef) as any)
  };
});

const { handleFullScreen, getWrapClassName, fullScreenRef } = useFullScreen({
  modalWrapperRef,
  extHeightRef,
  wrapClassName: toRef(getMergeProps.value, "wrapClassName")
});

const getProps = computed((): Recordable => {
  const opt = {
    ...unref(getMergeProps),
    visible: unref(visibleRef),
    okButtonProps: undefined,
    cancelButtonProps: undefined,
    title: undefined
  };
  return {
    ...opt,
    wrapClassName: unref(getWrapClassName)
  };
});
const attrs = useAttrs();
const getBindValue = computed((): Recordable => {
  const attr = {
    ...attrs,
    ...unref(getMergeProps),
    visible: unref(visibleRef),
    wrapClassName: unref(getWrapClassName)
  };

  if (unref(fullScreenRef)) {
    return omit(attr, ["height"]);
  }
  return attr;
});

const getWrapperHeight = computed(() => {
  if (unref(fullScreenRef)) return undefined;
  return unref(getProps).height;
});

// watchers
watchEffect(() => {
  visibleRef.value = !!props.visible;
  fullScreenRef.value = !!props.defaultFullscreen;
});

watch(
  () => unref(visibleRef),
  v => {
    emit("visible-change", v);
    emit("update:visible", v);
    instance && modalMethods.emitVisible?.(v, instance.uid);
    nextTick(() => {
      if (props.scrollTop && v && unref(modalWrapperRef)) {
        (unref(modalWrapperRef) as any).scrollTop();
      }
    });
  },
  {
    immediate: false
  }
);

async function handleCancel(e: Event) {
  e?.stopPropagation();
  if ((e.target as HTMLElement)?.classList?.contains(prefixCls + "-close--custom")) return;
  if (props.closeFunc && isFunction(props.closeFunc)) {
    const isClose: boolean = await props.closeFunc();
    visibleRef.value = !isClose;
    return;
  }

  visibleRef.value = false;
  emit("cancel", e);
}

function setModalProps(props: Partial<ModalProps>): void {
  propsRef.value = deepMerge(unref(propsRef) || ({} as any), props);
  if (Reflect.has(props, "visible")) {
    visibleRef.value = !!props.visible;
  }
  if (Reflect.has(props, "defaultFullscreen")) {
    fullScreenRef.value = !!props.defaultFullscreen;
  }
}

function handleOk(e: Event) {
  emit("ok", e);
}

function handleHeightChange(height: string) {
  emit("height-change", height);
}

function handleExtHeight(height: number) {
  extHeightRef.value = height;
}
</script>

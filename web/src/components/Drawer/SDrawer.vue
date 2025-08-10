<template>
  <a-drawer class="s-drawer" @close="onClose" v-bind="getBindValues">
    <template #[item]="data" v-for="item in getSlots">
      <slot :name="item" v-bind="data || {}"></slot>
    </template>
    <s-scrollbar :style="getScrollContentStyle" :wrapStyle="wrapStyle" v-loading="getLoading" :loading-tip="loadingText">
      <slot></slot>
    </s-scrollbar>
    <DrawerFooter v-bind="getProps" @close="onClose" @ok="handleOk" :height="getFooterHeight">
      <template #[item]="data" v-for="item in getFooterSlots">
        <slot :name="item" v-bind="data || {}"></slot>
      </template>
    </DrawerFooter>
  </a-drawer>
</template>

<script setup lang="ts">
import type { DrawerInstance, DrawerProps } from "./typing";
import type { CSSProperties } from "vue";
import { isFunction, isNumber } from "@/utils/is";
import { deepMerge } from "@/utils";
import DrawerFooter from "./components/DrawerFooter.vue";
import { basicProps } from "./props";
import { omit } from "lodash-es";
import { useWidgetSlots } from "@/hooks/useWidgetSlots";

defineOptions({
  inheritAttrs: false
});

const props = defineProps(basicProps);
const emit = defineEmits(["visible-change", "ok", "close", "register"]);
const { customSlots: getFooterSlots, slots } = useWidgetSlots([
  "insertFooter",
  "centerFooter",
  "appendFooter",
  "footer"
]);
const attrs = useAttrs();

const visibleRef = ref(false);
const propsRef = ref<Partial<DrawerProps>>({});

const getSlots = computed(() => Object.keys(omit(slots, ["default"])));

const getMergeProps = computed(
  (): DrawerProps => deepMerge(toRaw(props), unref(propsRef)) as DrawerProps
);

const getProps = computed(
  (): DrawerProps => ({
    placement: "right",
    ...unref(attrs),
    ...unref(getMergeProps),
    open: unref(visibleRef)
  })
);

const getBindValues = computed(() => ({
  ...attrs,
  ...unref(getProps),
  visible: unref(visibleRef)
}));

const drawerInstance: DrawerInstance = {
  setDrawerProps,
  emitVisible: undefined,
  props: getProps
};

const instance = getCurrentInstance();
instance && emit("register", drawerInstance, instance.uid);

const getFooterHeight = computed(() => {
  const { footerHeight, showFooter } = unref(getProps);
  if (showFooter && footerHeight) {
    return isNumber(footerHeight) ? `${footerHeight}px` : `${footerHeight.replace("px", "")}px`;
  }
  return "0px";
});

const getScrollContentStyle = computed(
  (): CSSProperties => ({
    position: "relative",
    height: `calc(100% - ${unref(getFooterHeight)})`
  })
);

const getLoading = computed(() => !!unref(getProps)?.loading);

watch(
  () => props.visible,
  (newVal, oldVal) => {
    if (newVal !== oldVal) visibleRef.value = !!newVal;
  },
  { deep: true }
);

watch(
  () => visibleRef.value,
  visible => {
    nextTick(() => {
      emit("visible-change", visible);
      instance && drawerInstance.emitVisible?.(visible, instance.uid);
    });
  }
);

async function onClose(e: Recordable) {
  const { closeFunc } = unref(getProps);
  emit("close", e);
  if (closeFunc && isFunction(closeFunc)) {
    const res = await closeFunc();
    visibleRef.value = !res;
    return;
  }
  visibleRef.value = false;
}

function setDrawerProps(props: Partial<DrawerProps>): void {
  propsRef.value = deepMerge(unref(propsRef) || {}, props) as DrawerProps;
  if (Reflect.has(props, "visible")) {
    visibleRef.value = !!props.visible;
  }
}

function handleOk() {
  emit("ok");
}
</script>

<style lang="less">
@header-height: 60px;
.s-drawer {
  .ant-drawer-wrapper-body {
    overflow: hidden;
  }

  .anticon-close {
    padding: 4px;
    border-radius: 3px;

    &:hover {
      background: #33333417;
    }
  }
  .ant-drawer-close {
    &:focus,
    &:hover {
      color: rgba(0, 0, 0, 0.45);
    }
    padding: 0;
  }
  .ant-drawer-body {
    height: calc(100% - @header-height);
    padding: 0;

    .scrollbar__wrap {
      padding:16px;
      margin-bottom: 0;
    }

    > .scrollbar > .scrollbar__bar.is-horizontal {
      display: none;
    }
  }
}
</style>

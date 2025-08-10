<template>
  <a-dropdown :trigger="trigger" v-bind="$attrs">
    <span>
      <slot></slot>
    </span>
    <template #overlay>
      <a-menu :selectedKeys="selectedKeys">
        <template v-for="item in options" :key="`${item.event}`">
          <a-menu-item
            v-bind="getAttr(item.event)"
            @click="handleClickMenu(item)"
            :disabled="item.disabled"
          >
            <a-popconfirm
              v-if="popconfirm && item.popConfirm"
              v-bind="getPopConfirmAttrs(item.popConfirm)"
            >
              <template #icon v-if="item.popConfirm.icon">
                <s-icon :type="item.popConfirm.icon" />
              </template>
              <div>
                <s-icon :type="item.icon" v-if="item.icon" />
                <span class="ml-1">{{ item.text }}</span>
              </div>
            </a-popconfirm>
            <template v-else>
              <s-icon :type="item.icon" v-if="item.icon" />
              <span class="ml-1">{{ item.text }}</span>
            </template>
          </a-menu-item>
          <a-menu-divider v-if="item.divider" :key="`d-${item.event}`" />
        </template>
      </a-menu>
    </template>
  </a-dropdown>
</template>

<script lang="ts" setup>
import type { DropMenu } from "./typing";
import { omit } from "lodash-es";
import { isFunction } from "@/utils/is";

const props = defineProps({
  popconfirm: Boolean,
  trigger: {
    type: [Array] as PropType<("contextmenu" | "click" | "hover")[]>,
    default: () => {
      return ["hover"];
    }
  },
  options: {
    type: Array as PropType<(DropMenu & Recordable)[]>,
    default: () => []
  },
  selectedKeys: {
    type: Array as PropType<string[]>,
    default: () => []
  }
});

const emit = defineEmits(["menuEvent"]);

function handleClickMenu(item: DropMenu) {
  const { event } = item;
  const menu = props.options.find(item => `${item.event}` === `${event}`);
  emit("menuEvent", menu);
  item.onClick?.();
}

const getPopConfirmAttrs = computed(() => {
  return (attrs: Recordable) => {
    const originAttrs = omit(attrs, ["confirm", "cancel", "icon"]);
    if (!attrs.onConfirm && attrs.confirm && isFunction(attrs.confirm)){
      originAttrs["onConfirm"] = attrs.confirm;
    }
    if (!attrs.onCancel && attrs.cancel && isFunction(attrs.cancel)){
      originAttrs["onCancel"] = attrs.cancel;
    }
    return originAttrs;
  };
});

const getAttr = (key: string | number) => ({ key });
</script>

<template>
  <div class="dict-label">
    <template v-if="isEmpty">
      <span> {{ emptyText }} </span>
    </template>
    <template v-else>
      <template v-if="dict?.widget_type == 'tag'">
        <a-tag :color="dict?.color">{{ dict.name }}</a-tag>
      </template>
      <template v-else-if="dict?.widget_type == 'badge'">
        <a-badge :color="dict?.color" :text="dict.name"></a-badge>
      </template>
      <template v-else>
        <span :style="{ color: dict?.color }">{{ dict.name }}</span>
      </template>
    </template>
  </div>
</template>

<script lang="ts" setup>
import { isObject, isEmptyObject } from "@/utils/is";

export type DictData = {
  name: string;
  widget_type: 'tag' | 'badge' | 'text'
  color?: string;
};

const props = defineProps({
  dict: {
    type: Object as PropType<DictData>,
    required: true
  },
  emptyText: {
    type: String,
    default: "-"
  }
});

// 判断字典数据是否为空
const isEmpty = computed(() => {
  const { dict } = props;
  // 如果字典数据为空对象或不包含 name 属性，则认为为空
  return !dict || !isObject(dict) || isEmptyObject(dict) || !dict.hasOwnProperty("name");
});
</script>
<style scoped>
.dict-label {
  display: inline-block;
}
</style>
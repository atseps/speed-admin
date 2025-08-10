<template>
  <a-form v-bind="$attrs">
    <template #[item]="data" v-for="item in componentSlots" :key="item">
      <slot :name="item" v-bind="data || {}"></slot>
    </template>
  </a-form>
</template>

<script lang="ts" setup>
import { useWidgetSlots } from "@/hooks/useWidgetSlots";
const { componentSlots } = useWidgetSlots();
</script>

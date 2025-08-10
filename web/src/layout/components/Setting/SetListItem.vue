<template>
  <a-list-item>
    <a-list-item-meta :title="title" />
    <template #actions>
      <slot></slot>
    </template>
  </a-list-item>
</template>
<script setup lang="ts">
defineProps({
  title: {
    type: String,
    required: true
  }
});
</script>

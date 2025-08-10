<template>
  <SDrawer v-bind="$attrs" @register="register" title="Drawer Title" width="50%">
    Drawer Info.
    <a-button type="primary" @click="closeDrawer"> 内部关闭drawer </a-button>
  </SDrawer>
</template>
<script lang="ts" setup>
  import {useDrawerInner } from '@/components/Drawer';

  const [register, { closeDrawer }] = useDrawerInner();
</script>

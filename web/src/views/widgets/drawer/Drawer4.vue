<template>
  <SDrawer v-bind="$attrs" @register="register" title="Drawer Title" width="50%">
    <div class="pt-3px pr-3px">
      <s-input label="field1" v-model="model.field1" ></s-input>
      <s-textarea label="field2" v-model="model.field2" ></s-textarea>
    </div>
  </SDrawer>
</template>
<script lang="ts" setup>
import { useDrawerInner } from '@/components/Drawer';

const model = reactive({
  field1: '',
  field2: '',
});

const [register] = useDrawerInner((data) => {
    model.field2 = data.data
    model.field1 = data.info
});

</script>

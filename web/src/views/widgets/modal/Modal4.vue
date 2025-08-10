<template>
  <SModal
    v-bind="$attrs"
    @register="register"
    title="Modal Title"
  >
    <div class="pt-3px pr-3px">
      <s-input label="field1" v-model="modelRef.field1" ></s-input>
      <s-textarea label="field2" v-model="modelRef.field2" ></s-textarea>
    </div>
  </SModal>
</template>
<script lang="ts" setup>
  import { useModalInner } from '@/components/Modal';

  const modelRef = ref({
    field1:'',
    field2:''
  });

  const [register] = useModalInner((data) => {
    data && onDataReceive(data);
  });

  function onDataReceive(data:Recordable) {
    modelRef.value = { field2: data.data, field1: data.info };
  }


</script>

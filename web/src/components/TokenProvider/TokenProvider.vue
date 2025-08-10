<template>
  <style-provider :transformers="[legacyLogicalPropertiesTransformer]">
    <slot />
  </style-provider>
</template>
<script setup lang="ts">
import { theme, StyleProvider, legacyLogicalPropertiesTransformer } from "ant-design-vue";
import { registerTokenToCSSVar } from "./token-to-cssvar";
const { token } = theme.useToken();
watchEffect(() => {
  registerTokenToCSSVar(token.value);
});
</script>

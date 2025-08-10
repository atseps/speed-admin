<template>
  <router-view v-slot="{ Component, route }">
    <transition v-if="openAnimation" :name="animation" mode="out-in" appear>
      <component :is="Component" :key="route.fullPath" />
    </transition>
    <component v-else :is="Component" :key="route.fullPath" />
  </router-view>
</template>
<script setup lang="ts">
import { useSetting } from "./hooks";
const { animation, openAnimation } = useSetting();
</script>

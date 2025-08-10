<template>
  <span class="s-breadcrumb__item">
    <span :class="['s-breadcrumb__inner', to ? 'is-link' : '']" ref="link" role="link" @click="onClick">
      <slot></slot>
    </span>
    <span class="s-breadcrumb__separator" role="presentation">{{ separator }}</span>
  </span>
</template>
<script setup lang="ts">
const router = useRouter();
const props = defineProps({
  separator: {
    type: String,
    default: "/"
  },
  to: {
    type: String
  },
  replace: Boolean
});
const link = ref();

const onClick = () => {
  if (!props.to || !router) return;
  props.replace ? router.replace(props.to) : router.push(props.to);
}

</script>
<style scoped lang="less">

.s-breadcrumb__item{
  float: left;
}

.s-breadcrumb__inner.is-link,
.s-breadcrumb__inner a {
  font-weight: 700;
  text-decoration: none;
  transition: color 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
  color: #303133;
}

.s-breadcrumb__separator {
  margin: 0 9px;
  color: #c0c4cc;
}

.s-breadcrumb__inner  {
  color: rgba(0, 0, 0, 0.45);
}

.s-breadcrumb__inner.is-link,
.s-breadcrumb__inner a {
  font-weight: 700;
  text-decoration: none;
  transition: color 0.2s cubic-bezier(0.645, 0.045, 0.355, 1);
  color: #303133;
}

.s-breadcrumb__item:last-child .s-breadcrumb__separator {
  display: none;
}
</style>

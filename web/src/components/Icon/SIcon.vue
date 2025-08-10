<template>
  <Icon :style="styleObj" v-if="svgIcon">
    <template #component="svgProps">
      <svg aria-hidden="true" v-bind="svgProps">
        <use :xlink:href="symbolId" />
      </svg>
    </template>
  </Icon>
  <component v-else-if="type" :is="type" v-bind="$attrs" :style="styleObj"></component>
</template>

<script lang="ts">
import * as antIcons from "@ant-design/icons-vue";
import { isNumber } from "@/utils/is"
export default defineComponent({
  components: {
    ...antIcons,
    Icon: antIcons.default
  },
  props: {
    type: {
      type: String,
      required: true
    },
    size: {
      type: [String, Number]
    },
    color: {
      type: String
    }
  },
  setup(props) {
    const svgIcon = computed(() => props.type?.startsWith("svg:"));

    const symbolId = computed(() => {
      return unref(svgIcon) ? `#icon-${props.type.split("svg:")[1]}` : props.type;
    });

    const styleObj = computed(() => {
      let size = "";
      if (props.size) {
        size = isNumber(props.size) ? `${props.size}px` : props.size;
      }
      return {
        fontSize: size,
        color: props.color
      };
    });
    return {
      styleObj,
      svgIcon,
      symbolId
    };
  }
});
</script>

<template>
  <div class="page-view">
    <a-page-header
      :title="pageTitle"
      @back="onBack"
      v-bind="$attrs"
      :ghost="ghost"
      :backIcon="backIcon"
      v-if="showHeader"
    >
      <template #default>
        <div class="text-secondary" v-if="description">
          {{ description }}
        </div>
        <slot name="content"></slot>
      </template>
      <template #[key] v-for="(_, key) in getSlots">
        <slot :name="key"></slot>
      </template>
    </a-page-header>
    <div class="content" :style="[bodyStyle, contentStyle]" ref="contentRef">
      <div class="page-header-index-wide">
        <a-card v-if="showCard" :bordered="bordered" :loading="loading">
          <slot></slot>
        </a-card>
        <slot v-else></slot>
      </div>
    </div>
  </div>
</template>
<script setup lang="tsx">
import { ArrowLeftOutlined } from "@ant-design/icons-vue";
import { getViewportOffset, getElementHeightWithMargin } from "@/utils/domUtils";
const props = defineProps({
  bordered: {
    type: Boolean,
    default: false
  },
  showCard: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  ghost: {
    type: Boolean,
    default: false
  },
  description: {
    type: String,
    default: ""
  },
  showHeader: {
    type: Boolean,
    default: false
  },
  isBack: {
    type: Boolean,
    default: true
  },
  title: {
    type: String
  },
  bodyStyle: {
    type: Object,
    default: () => {
      return {};
    }
  },
  autoHeight: {
    type: Boolean,
    default: false
  }
});

const route = useRoute();

const contentStyle = ref({});
const contentRef = ref();
const autoContentHeight = () => {
  const { top } = getViewportOffset(contentRef.value);
  const footerElement = document.querySelector(".layout-footer") as HTMLElement;
  const footerHeight = getElementHeightWithMargin(footerElement);
  contentStyle.value = {
    height: `calc(100vh - ${top + footerHeight}px)`
  };
};


onMounted(async () => {
  if(props.autoHeight){
    await nextTick();
    autoContentHeight();
  }
});

const pageTitle = computed(() => {
  return (props.title ? props.title : route.meta.title || "") as string;
});

const getSlots = computed(() => {
  const slots = { ...useSlots() };
  delete slots.default;
  return slots;
});

const $emit = defineEmits(["back"]);

const router = useRouter();

const backIcon = computed(() => {
  return props.isBack ? <ArrowLeftOutlined /> : false;
});

const onBack = (e: MouseEvent) => {
  if (props.isBack) {
    router.back();
  }
  $emit("back", e);
};

provide(
  "renderCard",
  computed(() => props.showCard)
);
</script>

<style lang="less" scoped>
.page-view {
  & .content,
  .page-header-index-wide,
  :deep(.ant-card) {
    height: 100%;
  }
  :deep(.ant-card-body) {
    height: 100%;
  }
}

.text-secondary {
  color: var(--ant-color-text);
}

:deep(.ant-page-header) {
  padding: 12px 24px;
}

:deep(.ant-page-header-heading-title) {
  font-size: 20px;
  line-height: 28px;
  font-weight: 500;
  color: var(--ant-color-text);
  flex: auto;
}

.content {
  padding: 16px 16px 0;
}

.extra-img {
  margin-top: -60px;
  text-align: center;
  width: 195px;

  img {
    width: 100%;
  }
}

.mobile {
  .extra-img {
    margin-top: 0;
    text-align: center;
    width: 96px;

    img {
      width: 100%;
    }
  }
}
</style>

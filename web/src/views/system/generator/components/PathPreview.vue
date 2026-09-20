<template>
  <div class="path-preview">
    <div class="path-preview__header">
      <span class="path-preview__title">生成目录预览</span>
    </div>
    <a-spin :spinning="loading">
      <s-scrollbar class="path-preview__body">
        <a-empty v-if="!list.length" :image="Empty.PRESENTED_IMAGE_SIMPLE" description="请完善生成配置" />
        <template v-else>
          <div v-for="group in groupedList" :key="group.name" class="path-group">
            <div class="path-group__title">
              <s-icon type="folder-open-outlined" />
              <span>{{ group.name }}</span>
            </div>
            <div v-for="item in group.items" :key="item.path" class="path-item">
              <s-icon type="file-outlined" class="path-item__icon" />
              <div class="path-item__main">
                <div class="path-item__path" :title="item.path">{{ item.path }}</div>
                <div class="path-item__desc">{{ item.description }}</div>
              </div>
            </div>
          </div>
        </template>
      </s-scrollbar>
    </a-spin>
  </div>
</template>

<script lang="ts" setup>
import { Empty } from "ant-design-vue";

/**
 * 生成目录项
 */
interface PathItem {
  /** 文件名 */
  name: string;
  /** 完整生成路径（相对项目根目录） */
  path: string;
  /** 归属分组，如「PHP 后端」 */
  group: string;
  /** 文件用途说明 */
  description: string;
}

/**
 * 分组后的目录项
 */
interface PathGroup {
  name: string;
  items: PathItem[];
}

const props = defineProps({
  /** 目录项列表 */
  list: {
    type: Array as PropType<PathItem[]>,
    default() {
      return [];
    }
  },
  /** 加载状态 */
  loading: {
    type: Boolean,
    default: false
  }
});

/**
 * 按分组聚合目录项，保持后端返回的原始顺序
 */
const groupedList = computed<PathGroup[]>(() => {
  const result: PathGroup[] = [];
  props.list.forEach(item => {
    const group = result.find(g => g.name === item.group);
    group ? group.items.push(item) : result.push({ name: item.group, items: [item] });
  });
  return result;
});
</script>

<style lang="less" scoped>
.path-preview {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.path-preview__header {
  display: flex;
  align-items: center;
  padding-bottom: 12px;
  margin-bottom: 12px;
  border-bottom: 1px solid #f0f0f0;
}

.path-preview__title {
  font-size: 14px;
  font-weight: 500;
  color: #262626;
}

.path-preview__body {
  flex: 1;
}

/* a-spin 包裹层需要参与 flex 布局并撑满剩余高度，
   才能把确定高度传递给内部 s-scrollbar（其容器为 height:100%） */
.path-preview :deep(.ant-spin-nested-loading) {
  flex: 1;
  min-height: 0;
}

.path-preview :deep(.ant-spin-container) {
  height: 100%;
}

.path-group {
  margin-bottom: 16px;
}

.path-group__title {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  color: #595959;
}

.path-item {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  padding: 4px 8px;
  border-radius: 4px;
  transition: background-color 0.2s;

  &:hover {
    background-color: #fafafa;
  }
}

.path-item__icon {
  margin-top: 2px;
  color: #8c8c8c;
  flex-shrink: 0;
}

.path-item__main {
  min-width: 0;
}

.path-item__path {
  font-family: "SFMono-Regular, Consolas, Monaco, monospace";
  font-size: 12px;
  color: #262626;
  line-height: 19px;
  word-break: break-all;
}

.path-item__desc {
  font-size: 12px;
  color: #bfbfbf;
  line-height: 18px;
}

</style>

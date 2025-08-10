<template>
  <a-modal title="代码预览" :width="1050" v-model:open="open" :footer="null" style="top: 50px">
    <a-tabs v-model:activeKey="activeTab">
      <a-tab-pane v-for="(item, index) in code" :tab="item.name" :key="`index${index}`">
        <div class="flex" style="height: 65vh">
          <s-scrollbar class="flex-1">
            <highlightjs autodetect :code="item.content" />
          </s-scrollbar>
          <div>
            <a-button @click="handleCopy(item.content)" type="link">
              <template #icon>
                <s-icon type="copy-outlined" />
              </template>
              复制
            </a-button>
          </div>
        </div>
      </a-tab-pane>
    </a-tabs>
  </a-modal>
</template>

<script setup lang="ts">
import useClipboard from "vue-clipboard3";
import { useVModel } from "@vueuse/core";
const { message } = useMessage();

type CodeType = {
  name: string;
  content: string;
};

const props = defineProps({
  modelValue: Boolean,
  code: {
    type: Array as PropType<CodeType[]>,
    default() {
      return [];
    }
  }
});

const { toClipboard } = useClipboard();

const emit = defineEmits(["update:modelValue"]);
const open = useVModel(props, "modelValue", emit);

const activeTab = ref("index0");

const handleCopy = async (text: string) => {
  try {
    await toClipboard(text);
    message.success("复制成功");
  } catch (e) {
    message.error("复制失败");
  }
};
</script>

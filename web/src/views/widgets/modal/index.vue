<template>
  <PageView title="modal组件使用示例">
    <a-space direction="vertical" size="middle" class="w-full">
      <a-card
        title="基本使用"
      >
        <a-button type="primary" class="my-4" @click="openModalLoading">
          打开弹窗,默认可拖动和全屏
        </a-button>
      </a-card>

      <a-card title="内外同时同时显示隐藏">
        <a-button type="primary" class="my-4" @click="openModal2"> 打开弹窗 </a-button>
      </a-card>

      <a-card title="自适应高度">
        <a-button type="primary" class="my-4" @click="openModal3"> 打开弹窗 </a-button>
      </a-card>

      <a-card title="内外数据交互">
        <a-button type="primary" class="my-4" @click="send"> 打开弹窗并传递数据 </a-button>
      </a-card>

    </a-space>

    <Modal1 @register="register1" :minHeight="100" />
    <Modal2 @register="register2" />
    <Modal3 @register="register3" />
    <Modal4 @register="register4" />
  </PageView>
</template>
<script lang="ts" setup>
import { useModal } from "@/components/Modal";
import Modal1 from "./Modal1.vue";
import Modal2 from "./Modal2.vue";
import Modal3 from "./Modal3.vue";
import Modal4 from "./Modal4.vue";

const [register1, { openModal: openModal1 }] = useModal();
const [register2, { openModal: openModal2 }] = useModal();
const [register3, { openModal: openModal3 }] = useModal();
const [register4, { openModal: openModal4 }] = useModal();

function send() {
  openModal4(true, {
    data: "content",
    info: "Info"
  });
}

function openModalLoading() {
  openModal1(true);
}



</script>

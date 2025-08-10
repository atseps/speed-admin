<template>
  <s-modal-form v-bind="getBindValue" ref="modalRef">
    <s-tree-select
      label="上级菜单"
      name="pid"
      class="col-span-2"
      labelField="title"
      :treeData="treeData"
      placeholder="请选择上级菜单"
      v-model="form.pid"
    />

    <s-radio-group label="菜单类型" name="type" v-model="form.type" @change="onRadioChange">
      <a-radio :value="0">目录</a-radio>
      <a-radio :value="1">菜单</a-radio>
      <a-radio :value="2">权限</a-radio>
    </s-radio-group>

    <s-radio-group
      label="打开方式"
      name="open_type"
      v-if="form.type == 1"
      :disabled="openDisable"
      v-model="form.open_type"
      @change="onRadioChange"
    >
      <a-radio :value="0">组件</a-radio>
      <a-radio :value="1">内链</a-radio>
      <a-radio :value="2">外链</a-radio>
    </s-radio-group>

    <s-input
      label="菜单名称"
      name="title"
      class="col-span-2"
      v-model="form.title"
      placeholder="请输入菜单名称"
    />

    <s-input
      :label="labelText"
      name="path"
      v-if="form.type != 2"
      v-model="form.path"
      :placeholder="`请输入${labelText}`"
    />

    <a-form-item name="component" v-if="form.type != 2 && form.open_type == 0">
      <template #label>
        路由组件
        <a-tooltip :overlayInnerStyle="{ width: '300px' }">
          <template #title>
            一、如果类型是目录，可选值：
            <br />
            ① Layout 包含头部、菜单、底部的布局
            <br />
            ② RouteView 不包含任何布局的路由出口
            <br />
            二、如果类型是菜单，输入组件的路径
            <br />
             例如：system/menu/index
          </template>
          <s-icon class="ml-[2px] text-gray-400" type="QuestionCircleOutlined" size="14" />
        </a-tooltip>
      </template>
      <a-auto-complete
        v-if="form.type == 0"
        v-model:value="form.component"
        :options="options"
        placeholder="请输入路由组件"
      />
      <a-input v-else v-model:value="form.component" placeholder="请输入路由组件" />
    </a-form-item>

    <s-input
      label="内链地址"
      name="link_url"
      v-if="form.open_type == 1"
      v-model="form.link_url"
      placeholder="请输入内链地址"
    />

    <s-input
      name="rules"
      :rules="[{ required: true, message: '请输入权限节点' }]"
      v-if="form.type == 2"
      v-model="form.rules"
      placeholder="请输入权限节点"
    >
      <template #label>
        权限节点
        <a-tooltip>
          <template #title> 输入格式：模块:控制器:方法 </template>
          <s-icon class="ml-[2px] text-gray-400" type="QuestionCircleOutlined" size="14" />
        </a-tooltip>
      </template>
    </s-input>

    <s-input
      name="active_key"
      v-model="form.active_key"
      v-if="form.type != 2 && form.open_type == 0"
      placeholder="请输入高亮导航的完整路由地址"
    >
      <template #label>
        高亮导航
        <a-tooltip title="访问当前页面时，指定某个菜单处于高亮状态">
          <s-icon class="ml-[2px] text-gray-400" type="QuestionCircleOutlined" size="14" />
        </a-tooltip>
      </template>
    </s-input>

    <s-input-number label="菜单排序" name="sort" max="9999" v-model="form.sort" placeholder="请输入菜单排序"/>

    <a-form-item label="菜单图标" name="icon" v-if="form.type != 2" placeholder="请输入菜单图标">
      <s-icon-select v-model="form.icon" />
    </a-form-item>

    <s-radio-group label="是否显示" v-model="form.hidden" name="hidden" v-if="form.type != 2">
      <a-radio :value="0">显示</a-radio>
      <a-radio :value="1">隐藏</a-radio>
    </s-radio-group>

    <s-radio-group
      label="隐藏子菜单"
      v-model="form.hide_children"
      name="hide_children"
      v-if="form.open_type == 0 && form.type != 2"
    >
      <a-radio :value="1">是</a-radio>
      <a-radio :value="0">否</a-radio>
    </s-radio-group>
  </s-modal-form>
</template>
<script lang="ts" setup>
import { save } from "@/api/system/menu";
import { isURL } from "@/utils/is";
import type { RuleObject } from 'ant-design-vue/es/form';
import type { TreeData } from './index.vue';

interface Option {
  value: string;
}

defineProps({
  treeData: {
    type: Array as PropType<TreeData[]>,
    default: () => []
  }
});

const form = reactive({
  id: undefined,
  pid: undefined,
  sort: undefined,
  type: 0,
  hidden: 0,
  hide_children: 0,
  path: "",
  icon: "",
  rules: "",
  title: "",
  component: "",
  active_key: "",
  open_type: 0,
  link_url: ""
});

const modalRef = ref();

const options = ref<Option[]>([{ value: "Layout" }, { value: "RouteView" }]);

const labelText = computed(() => {
  return form.open_type == 2 ? "外链地址" : "路由地址";
});

const rules = reactive<{ [key: string]: RuleObject[] }>({
  pid: [{ required: true, message: "请选择上级菜单" }],
  title: [{ required: true, message: "请输入标题" }],
  component: [{ required: true, message: "请输入路由组件" }],
  path: [
    { required: true, message: `请输入${labelText.value}` },
    {
      validator: (_, value) => {
        if (form.open_type == 2) {
          if (value && !isURL(value)) {
            return Promise.reject("请输入有效的URL地址");
          }
        }
        return Promise.resolve();
      }
    }
  ],
  link_url: [
    {
      required: true,
      message: "请输入链接地址"
    },
    {
      type: "url",
      message: "请输入有效的URL地址"
    }
  ]
});

const onRadioChange = () => {
  unref(modalRef).getFormRef().clearValidate();
};

const beforeSubmit = (formData: typeof form) => {
  if (formData.open_type == 1) {
    formData.component = "Iframe";
  }
};

const openDisable = computed(() => {
  if (form.type != 1) {
    form.open_type = 0;
    return true;
  } else {
    return false;
  }
});

const getBindValue = computed(() => {
  return {
    width: 710,
    title: "菜单",
    model: form,
    saveApi: save,
    destroyOnClose: true,
    beforeSubmit: beforeSubmit,
    bodyStyle: { padding: "10px" },
    rules: rules,
    labelWidth: '100px',
    layoutGrid: { col: 2, gutter: 16 }
  };
});

</script>

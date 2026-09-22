# SpeedAdmin 前端组件详细用法

> 本文件是 `speed-admin-coding` 技能的详细参考，编写前端代码、使用核心组件时按需查阅。

## 1. STable 表格组件

### 搜索表单配置

搜索表单通过 `searchForm` 配置数组实现，每个配置项包含以下属性：

**基本属性**：

- `title` - 搜索字段的显示名称
- `dataIndex` - 对应的数据字段名（与后端接口参数对应）
- `component` - **必须使用** 组件类型（详见下方组件列表）
- `props` - 组件的配置参数（组件特定的配置项）

**搜索表单支持的组件类型**：

```javascript
type Component =
    'Input' |           // 输入框
    'InputSearch' |     // 搜索输入框
    'Textarea' |       // 文本域
    'InputNumber' |     // 数字输入框
    'Select' |          // 下拉框
    'TreeSelect' |      // 树形选择
    'Switch' |          // 开关
    'RadioGroup' |      // 单选组
    'Checkbox' |        // 复选框
    'Cascader' |        // 级联选择
    'DatePicker' |      // 日期选择器
    'MonthPicker' |     // 月份选择器
    'RangePicker' |     // 日期范围选择器
    'WeekPicker' |      // 周选择器
    'TimePicker' |      // 时间选择器
    'TableSelect' |     // 表格选择器
    'CompactSelect'      // 紧凑选择器
```

**配置示例**：

```vue
<template>
  <s-table @register="register" />
</template>

<script lang="ts" setup>
const columns = [
  { title: '姓名', dataIndex: 'name', key: 'name' },
  { title: '年龄', dataIndex: 'age', key: 'age' },
];
const roleOptions = ref([
  { id: 1, name: '管理员' },
  { id: 2, name: '普通用户' },
]);
// 搜索表单配置 - 使用 component 属性指定组件类型
const searchForm = ref([
  {
    title: "用户名称",        // 搜索字段的显示名称
    dataIndex: "key",        // 对应的数据字段名
    props: {                 // 组件的配置参数
      placeholder: "请输入姓名、拼音搜索"
    }
    // component 默认值为 "Input"，所以这里可以省略
  },
  {
    title: "角色",           // 搜索字段的显示名称
    dataIndex: "role_id",    // 对应的数据字段名
    component: "Select",      // 使用下拉框组件
    props: {                 // 组件的配置参数
      allowClear: true,
      placeholder: "请选择角色",
      fieldNames: { label: "name", value: "id" },
      options: roleOptions    // 下拉选项（可以是响应式数据）
    }
  },
  {
    title: "用户状态",       // 搜索字段的显示名称
    dataIndex: "status",     // 对应的数据字段名
    component: "Select",     // 使用下拉框组件
    props: {                 // 组件的配置参数
      placeholder: "请选择状态",
      options: [             // 下拉选项（静态数组）
        { value: 0, label: "禁用" },
        { value: 1, label: "激活" }
      ]
    }
  },
  {
    title: "添加时间",       // 搜索字段的显示名称
    dataIndex: "create_time", // 对应的数据字段名
    component: "RangePicker"  // 使用日期范围选择器
    // 组件配置可以直接放在 props 中，也可以省略
  },
  {
    title: "供应商",         // 搜索字段的显示名称
    dataIndex: "supplier_id", // 对应的数据字段名
    component: "TableSelect", // 使用表格选择器组件
    props: {                 // 组件的配置参数
      placeholder: "请选择供应商",
      labelField: "name",    // 显示字段
      valueField: "id",      // 值字段
      api: "supplier",       // API 接口地址（支持字符串和函数形式）
      columns: [            //表格列配置
          { title: "供应商名称", dataIndex: "name" },
          { title: "联系人", dataIndex: "contact_name" },
          { title: "联系电话", dataIndex: "contact_phone" }
      ]
    }
  }
]);

const [register, { refresh }] = useTable({
  columns,
  searchForm,
  listApi: getUserList,
});
</script>
```

**常见配置说明**：

1. **Select 下拉框**：
   - `props.options` - 下拉选项，格式为 `[{ label: '选项1', value: 1 }, ...]`
   - `props.fieldNames` - 自定义字段映射，如 `{ label: 'name', value: 'id' }`
2. **TableSelect 表格选择器**：
   - `props.api` - API 接口地址（字符串形式，如 `"supplier"`）
   - `props.labelField` - 显示的文本字段（默认 `"name"`）
   - `props.valueField` - 值字段（默认 `"id"`）
   - `props.columns` - 表格列配置
   - `props.multiple` - 是否多选（默认 `false`）
3. **RangePicker 日期范围选择器**：
   - 会自动添加预设快捷选项（今天、本周、本月等）

**注意事项**：

- **必须使用 `component` 属性**，而不是 `type` 属性
- 组件名称必须与组件映射表一致（如 `Select`、`RangePicker`、`TableSelect`）
- 组件的配置参数必须放在 `props` 对象中
- `api` 属性在 `TableSelect` 中可以是字符串或者函数形式

**错误示例**：

```javascript
// ❌ 错误：使用了 type 而不是 component
{
  title: "客户",
  dataIndex: "customer_id",
  type: "TableSelect",        // 错误：应该使用 component
  api: "getCustomerList" // 错误：应该放在 props.api 中
}

// ❌ 错误：options 没有放在 props 中
{
  title: "状态",
  dataIndex: "status",
  component: "Select",
  options: [...]         // 错误：应该放在 props.options 中
}
```

**正确示例**：

```javascript
// ✅ 正确：使用 component 和 props
{
  title: "客户",
  dataIndex: "customer_id",
  component: "TableSelect",
  props: {
    api: "customer",
    labelField: "name",
    valueField: "id",
    placeholder: "请选择客户",
  }
}

// ✅ 正确：options 放在 props 中
{
  title: "状态",
  dataIndex: "status",
  component: "Select",
  props: {
    placeholder: "请选择状态",
    options: [
      { value: 1, label: "已审核" },
      { value: 0, label: "未审核" }
    ]
  }
}
```

### 列表页完整示例（toolbar + 多选 + 删除）

```vue
<template>
  <s-table @register="register">
    <template #toolbar>
      <a-button type="primary" @click="handleAdd">添加</a-button>
    </template>
  </s-table>
</template>

<script lang="ts" setup>
import { index, del } from "@/api/user";

const columns = ref([
  { title: "姓名", dataIndex: "name", key: "name" },
  { title: "年龄", dataIndex: "age", key: "age" },
]);

const [register, { refresh, getSelectRows }] = useTable({
  columns,
  listApi: index,
  deleteApi: del,
  showIndex: true,
  selection: true,
});
</script>
```

**useTable 方法**：

- `setProps(props)` - 设置表格参数
- `refresh(opt?)` - 刷新表格
- `search(opt?)` - 搜索表格（不清除排序过滤）
- `getDataSource()` - 获取表格数据
- `clearSelectedRowKeys()` - 清空选中行
- `getSelectRows()` - 获取选中行数据
- `getSelectRowKeys()` - 获取选中行keys
- `handleDelete(id?)` - 删除行数据
- `setColumns(columns)` - 设置列配置

## 2. SForm 表单组件

```vue
<template>
  <s-form ref="formRef" :model="formData" :label-col="{ span: 4 }">
    <s-input label="名称" v-model="formData.name" required />
    <s-select label="类型" v-model="formData.type" :api="getTypeList" />
    <s-date-picker label="日期" v-model="formData.date" />
  </s-form>
</template>

<script lang="ts" setup>
const formData = ref({
  name: "",
  type: "",
  date: "",
});
</script>
```

**表单组件列表**：

- `s-input` - 输入框
- `s-input-number` - 数字输入框
- `s-textarea` - 文本域
- `s-select` - 下拉框
- `s-radio-group` - 单选框组
- `s-checkbox-group` - 多选框组
- `s-tree-select` - 树形选择
- `s-date-picker` - 日期选择器

**扩展属性（Select/Radio/Checkbox/TreeSelect）**：

- `dictType` - 字典类型
- `api` - 接口函数
- `params` - 接口参数
- `resultField` - 返回数据字段（默认data）
- `labelField` - 文本字段（默认label）
- `valueField` - 值字段（默认value）

## 3. SModalForm 弹窗表单组件

```vue
<!-- form.vue -->
<template>
  <s-modal-form v-bind="getBindValue">
    <s-input label="名称" name="name" v-model="form.name" />
    <s-select label="类型" name="type" v-model="form.type" :options="options" />
    <s-textarea label="备注" name="note" v-model="form.note" />
  </s-modal-form>
</template>

<script lang="ts" setup>
import { save, edit } from "@/api/user";

const form = reactive({
  id: "",
  name: "",
  type: "",
  note: "",
});

const rules = reactive({
  name: [{ required: true, message: "请输入名称" }],
  type: [{ required: true, message: "请选择类型" }],
});

const options = ref([
  { value: 1, label: "类型一" },
  { value: 2, label: "类型二" },
]);

const getBindValue = computed(() => {
  return {
    width: 540,
    title: "用户",
    model: form,
    saveApi: save,
    rules: rules,
    layout: "vertical",
    readApi: edit,
  };
});
</script>

<!-- index.vue -->
<template>
  <s-table @register="register">
    <template #toolbar>
      <a-button type="primary" @click="openModal(true)">添加</a-button>
    </template>
    <template #bodyCell="{ column, record }">
      <template v-if="column?.dataIndex === 'action'">
        <a @click="openModal(true, record.id)">修改</a>
      </template>
    </template>
  </s-table>
  <user-form @register="registerModal" @save-success="refresh" />
</template>

<script lang="ts" setup>
import userForm from "./form.vue";
import { index } from "@/api/user";

const columns = [
  { title: "ID", dataIndex: "id" },
  { title: "名称", dataIndex: "name" },
  { title: "操作", dataIndex: "action" },
];

const [register, { refresh }] = useTable({
  columns,
  listApi: index,
});

const [registerModal, { openModal }] = useModal();
</script>
```

**SModalForm 属性**：

- `saveApi` - 保存接口
- `readApi` - 数据回显接口（edit接口）
- `width` - 弹窗宽度
- `title` - 弹窗标题（自动补全添加/编辑）
- `model` - 表单数据对象
- `rules` - 表单验证规则
- `layout` - 表单布局（horizontal/vertical/inline）
- `showLoading` - 是否显示加载动画
- `beforeSubmit` - 提交前回调
- `beforeSetFormValue` - 设置表单值之前的回调

**事件**：

- `saveSuccess` - 保存成功事件
- `cancel` - 关闭事件
- `finish` - 验证成功后回调
- `loadSuccess` - 数据加载成功事件

## 4. SDescription 详情组件

```vue
<template>
  <s-description
    title="用户详情"
    :column="3"
    :data="userData"
    :schema="schema"
  />
</template>

<script lang="ts" setup>
const userData = ref({
  id: 1,
  name: "张三",
  age: 25,
  email: "test@example.com",
  phone: "13800138000",
  address: "北京市朝阳区",
});

const schema = [
  { field: "id", label: "ID" },
  { field: "name", label: "姓名" },
  { field: "age", label: "年龄" },
  { field: "email", label: "邮箱" },
  { field: "phone", label: "电话" },
  { field: "address", label: "地址" },
];
</script>
```

**SDescription 属性**：

- `title` - 标题
- `column` - 一行显示的列数
- `data` - 数据源
- `schema` - 详情项配置
- `bordered` - 是否显示边框
- `useCollapse` - 是否使用折叠容器
- `canExpand` - 是否可折叠

**Schema 配置项**：

- `field` - 字段名
- `label` - 标签名
- `span` - 占用列数
- `show` - 动态显示判断函数
- `render` - 自定义渲染函数

## 5. SModal 弹窗组件

```vue
<!-- ModalContent.vue -->
<template>
  <s-modal v-bind="$attrs" title="标题">
    <div>弹窗内容</div>
  </s-modal>
</template>

<!-- Page.vue -->
<template>
  <div>
    <ModalContent @register="register" />
    <a-button @click="openModal(true)">打开弹窗</a-button>
  </div>
</template>

<script lang="ts" setup>
import ModalContent from "./ModalContent.vue";

const [register, { openModal }] = useModal();
</script>
```

**useModal 方法**：

- `openModal(visible, data?)` - 打开/关闭弹窗
- `closeModal()` - 关闭弹窗
- `setModalProps(props)` - 设置弹窗属性

**useModalInner 方法（弹窗内部）**：

- `closeModal()` - 关闭弹窗
- `changeOkLoading(loading)` - 修改确认按钮loading
- `changeLoading(loading)` - 修改弹窗loading
- `setModalProps(props)` - 设置弹窗属性

## 6. SDrawer 抽屉组件

```vue
<!-- DrawerContent.vue -->
<template>
  <s-drawer v-bind="$attrs" title="标题" width="50%">
    <div>抽屉内容</div>
  </s-drawer>
</template>

<!-- Page.vue -->
<template>
  <div>
    <DrawerContent @register="register" />
    <a-button @click="openDrawer(true)">打开抽屉</a-button>
  </div>
</template>

<script lang="ts" setup>
import DrawerContent from "./DrawerContent.vue";

const [register, { openDrawer }] = useDrawer();
</script>
```

**useDrawer/useDrawerInner 方法同 useModal**。

## 7. SUpload 上传组件

```vue
<template>
  <!-- 头像上传 -->
  <SUpload show-type="avatar" v-model:fileUrl="avatarUrl" />

  <!-- 单图上传 -->
  <SUpload show-type="image" :multiple="false" v-model:fileUrl="imageUrl" />

  <!-- 多图上传 -->
  <SUpload show-type="image" v-model:fileUrl="imageUrls" />

  <!-- 按钮上传 -->
  <SUpload
    show-type="button"
    v-model:fileList="fileList"
    action="/upload/file"
  />
</template>
```

## API 定义规范（遵循后端资源路由）

**后端资源路由规则**：

- `GET /resource` - index（列表）
- `GET /resource/create` - create（获取创建数据）
- `POST /resource` - save（保存/新增）
- `GET /resource/:id` - read（查看详情）
- `GET /resource/:id/edit` - edit（获取编辑数据）
- `PUT /resource/:id` - update（更新）
- `DELETE /resource/:id` - delete（删除）

**前端 API 定义示例**：

```typescript
// api/user.ts
import { request } from "@/utils/request";

// 列表接口 GET /user
export function index(params: any) {
  return request.get({ url: "/user", params });
}

// 获取创建数据 GET /user/create
export function create() {
  return request.get({ url: "/user/create" });
}

// 保存接口  /user
export function save(data: Recordable) {
  const url = data.id ? `user/${data.id}` : "user";
  const method = data.id ? "put" : "post";
  return request[method](url, data);
}

// 查看详情 GET /user/:id
export function read(id: string | number) {
  return request.get({ url: `/user/${id}` });
}

// 获取编辑数据 GET /user/:id/edit
export function getEdit(id: string | number) {
  return request.get(`user/${id}/edit`);
}

// 删除接口 DELETE /user/:id
export function del(id: string | number) {
  return request.delete({ url: `/user/${id}` });
}

// 批量删除 DELETE /user (传递id数组)
export function batchDelete(ids: (string | number)[]) {
  return request.delete({ url: "/user", data: { ids } });
}
```

## 暗黑主题适配

**方式1：使用CSS变量**

```less
.container {
  background: var(--ant-color-bg-container);
  color: var(--ant-color-text);
}
```

**方式2：使用选择器**

```less
[data-theme="dark"] {
  .container {
    background: #000;
  }
}
```

**方式3：使用Tailwind CSS**

```vue
<div class="bg-white dark:bg-black">内容</div>
```

**常用CSS变量**：

- `--ant-color-primary` - 主色调
- `--ant-color-bg-container` - 容器背景
- `--ant-color-bg-layout` - 布局背景
- `--ant-color-border` - 边框颜色
- `--ant-color-text` - 文本颜色
- `--ant-color-text-secondary` - 次级文本

## 自动导入规则

已配置自动导入，无需手动引入：

- Vue：`ref`, `computed`, `watch`, `onMounted` 等
- Vue Router: `useRouter`, `useRoute`
- Ant Design Vue: `a-button`, `a-form`, `a-input` 等
- 公共组件: `src/components/` 下所有组件
- Hooks: `src/hooks/web/` 下组合式函数

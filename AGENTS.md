## 交互要求

1. 输出的所有回答和思考过程必须全部使用中文，不能使用英文，代码语法英文关键词除外。
2. 不要格式化代码，保持原始代码格式。
3. 所有方法/函数必须包含清晰的文档注释（如 PHPDoc 或 JSDoc），说明功能、参数和返回值，Vue的模版代码不需要注释；
   方法内部的关键逻辑、复杂表达式或非显而易见的操作，必须添加行内注释或块注释，解释“为什么这么做”或“这一步在做什么”；
   注释应简洁、准确，避免冗余，用中文书写；
   优先保证代码可读性和可维护性。

# Speed Admin - Agent Coding Guidelines

- **前端**: Vue 3 + TypeScript + Vite + Ant Design Vue + Pinia
- **后端**: PHP 8.0+ + ThinkPHP 8.1
- **数据库**: MySQL (ThinkORM)

***

## 构建 / Lint / 测试命令

### 前端 (web/ 目录)

```bash
npm install          # 安装依赖
npm run dev          # 开发服务器
npm run build        # 生产构建
npm run preview      # 预览构建结果
npm run format       # Prettier 格式化
npm run lint         # ESLint 检查并自动修复
```

### 后端 (根目录)

```bash
composer install         # 安装 PHP 依赖
php think <command>      # 运行 ThinkPHP 命令
```

### 运行单个 ESLint 检查

```bash
npx eslint web/src/components/Table/STable.vue --fix  # 检查单个文件
npx eslint web/src/views/user --fix                   # 检查单个目录
```

***

## 代码风格指南

### 前端 (Vue 3 + TypeScript)

#### 文件结构

```
web/src/
├── api/              # API接口定义
├── assets/           # 资源文件
├── components/       # 公共组件（自动导入）
├── config/           # 项目配置
├── directives/       # 自定义指令
├── hooks/            # 组合式函数
├── layout/           # 布局组件
├── router/           # 路由配置
├── store/            # Pinia状态管理
├── styles/           # 全局样式
├── utils/            # 工具函数
├── views/            # 页面组件
└── main.ts           # 入口文件
```

#### 组件结构

```vue
<template></template>

<script lang="ts" setup>
import { ref, computed } from "vue";

const loading = ref(false);
const isDisabled = computed(() => loading.value);

function handleSubmit() {}
</script>

<style lang="less" scoped></style>
```



#### TypeScript 规范

- 优先显式类型标注，使用接口定义对象结构，避免使用 `any`
- 使用 `<script setup>` 语法

#### 组件命名

- 文件名: PascalCase (如 `UserForm.vue`)
- 使用: pascal-case (如 `<user-form />`)
- 自定义组件使用 `S` 前缀 (如 `STable`, `SForm`)

***

### 后端 (PHP/ThinkPHP)

#### 文件结构

```
├─📂 app                 # 后端根目录
│  ├─📂 adminapi         # adminapi应用目录（后台管理接口）
│  ├─📂 api              # api应用目录
│  ├─📂 model            # 模型目录
│  ├─📂 service          # 服务层目录（业务逻辑）
├─📂 config              # 配置目录
├─📂 core                # 核心扩展目录（公共类库封装）
├─📂 router              # 路由文件夹
├─📂 public              # WEB目录（对外访问目录）
│  │  ├─📄 index.php     # php入口文件
├─📄 .env                # 项目环境配置文件
```

## 核心扩展目录结构

```
├─📂 core
│  ├─📂 base            # 基类
│  ├─📂 facade          # 门面
│  ├─📂 service         # 服务类
│  ├─📂 traits          # 组合
│  ├─📂 utils           # 工具
│  ├─📄 AppService.php  # 应用服务类
│  ├─📄 BasisQuery.php  # db查询类
│  ├─📄 ModelCollection.php  # 模型集合类
│  └─📄 Permissions.php  # 权限认证类
```


#### 控制器结构

```php
<?php
namespace app\adminapi\controller\product;

use core\base\BaseController;
use app\service\product\ProductService;

class Product extends BaseController
{
    private $service;

    public function __construct(ProductService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }
}
```

#### 命名规范

| 类型        | 规范          | 示例                              |
| --------- | ----------- | ------------------------------- |
| 控制器/模型/服务 | PascalCase  | Product.php, ProductService.php |
| 方法        | camelCase   | getList()                       |
| 数据库表      | snake\_case | eb\_product                     |

#### 错误处理

- 使用 ThinkPHP 内置异常处理和 Validate 类
- 通过 `$this->success()` / `$this->error()` 返回响应
- 业务异常使用core\exception\FailedException;类抛出
***

## 常用任务

### 新增 CRUD 功能

**后端:** Model → Validate → Service → Controller → 路由
**前端:** API 方法 → 表单组件 → 路由

***

## 环境变量

### 前端 (web/.env)

### 后端 (.env)



## description: SpeedAdmin前端代码生成助手

## 核心组件使用规范

### 1. STable 表格组件

#### 搜索表单配置

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

- **必须使用** **`component`** **属性**，而不是 `type` 属性
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

### 2. SForm 表单组件

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
- `s-tree-select` - 栀选择
- `s-date-picker` - 日期选择器

**扩展属性（Select/Radio/Checkbox/TreeSelect）**：

- `dictType` - 字典类型
- `api` - 接口函数
- `params` - 接口参数
- `resultField` - 返回数据字段（默认data）
- `labelField` - 文本字段（默认label）
- `valueField` - 值字段（默认value）

### 3. SModalForm 弹窗表单组件

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

### 4. SDescription 详情组件

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

### 5. SModal 弹窗组件

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

### 6. SDrawer 抽屉组件

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

**useDrawer/useDrawerInner 方法同useModal**

### 7. SUpload 上传组件

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

## API定义规范（遵循后端资源路由）

**后端资源路由规则**：

- `GET /resource` - index（列表）
- `GET /resource/create` - create（获取创建数据）
- `POST /resource` - save（保存/新增）
- `GET /resource/:id` - read（查看详情）
- `GET /resource/:id/edit` - edit（获取编辑数据）
- `PUT /resource/:id` - update（更新）
- `DELETE /resource/:id` - delete（删除）

**前端API定义示例**：

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

## 自动导入规则

已配置自动导入，无需手动引入：

- Vue、Vue: `ref`, `computed`, `watch`, `onMounted` 等
- Vue Router: `useRouter`, `useRoute`
- Ant Design Vue: `a-button`, `a-form`, `a-input` 等
- 公共组件: `src/components/` 下所有组件
- Hooks: `src/hooks/web/` 下组合式函数

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

## 常用CSS变量

- `--ant-color-primary` - 主色调
- `--ant-color-bg-container` - 容器背景
- `--ant-color-bg-layout` - 布局背景
- `--ant-color-border` - 边框颜色
- `--ant-color-text` - 文本颜色
- `--ant-color-text-secondary` - 次级文本

## 命名规范

- **文件名**: kebab-case (如 `user-form.vue`)
- **组件引用**: PascalCase (如 `<UserForm />`)
- **自定义组件**: 使用 `S` 前缀 (如 `STable`, `SForm`)
- **API方法**: 遵循资源路由命名 (index, create, save, read, edit, update, delete)



## 注意事项

1. **组件必须包含清晰的文档注释**（JSDoc）
2. **方法内部关键逻辑必须添加注释**
3. **注释全部使用中文**
4. **不要格式化代码**
5. **使用 TypeScript 类型标注**
6. **优先使用组合式函数（useTable、useModal等）**
7. **v-model 统一语法，不使用 v-model:value**
8. **API命名严格遵循资源路由规范**

- 使用 Ant Design Vue + Pinia + Axios + lodash-es
- 组件从 `web/src/components/` 自动导入
- `@/` 别名指向 `web/src/`

***

***

## description: SpeedAdmin后端代码生成助手


## 技术栈

- PHP 8.0+
- ThinkPHP 8.1
- MySQL
- JWT（JSON Web Token）
- RBAC（基于角色的访问控制）


## 控制器开发规范

### 控制器基类

继承 `core\base\BaseController`

```php
<?php
namespace app\adminapi\controller\product;

use core\base\BaseController;
use app\service\product\ProductService;

class Product extends BaseController
{
    private $service;

    public function __construct(ProductService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getList();
        $this->success($data);
    }
}
```

### 响应方法

- `$this->success($data, $msg = '', $code = 200)` - 成功响应
- `$this->error($msg = '', $code = 400, $data = [])` - 失败响应

## 模型开发规范

### 模型基类

继承 `core\base\BaseModel`

```php
<?php
namespace app\model\system;

use core\base\BaseModel;

class User extends BaseModel
{
    protected $table = 'eb_user';

    // 定义搜索器
    public function searchNameAttr($query, $value)
    {
        $query->whereLike('name', trim($value));
    }

    public function searchStatusAttr($query, $value)
    {
        $query->where('status', $value);
    }
}
```

### 模型扩展方法（BaseModelTrait）

```php
// 新增单条数据
public function storeBy(array $data){}

// 循环插入数据时使用
public function createBy(array $data){}

// 更新数据
public function updateBy($id, $data, $field = ''): bool{}

// 查找数据
// 参数: id, 查询字段, 是否可查询软删除数据
public function findBy($id, array $field = ['*'], $trash = false){}

// 删除数据（force true 可物理删除）
public function deleteBy($id, $force = false){}

// 批量插入数据
public function insertAllBy(array $data){}

// 软删除恢复
public function recover($id){}

// 禁用/启用（默认使用status字段）
public function disOrEnable($id, $field = 'status'){}
```

### 搜索器

**定义搜索器**（以 search + FieldName + Attr 形式）

```php
// 关键词搜索
public function searchKeyAttr($query, $value)
{
    $query->whereLike('key', trim($value));
}

// 名称搜索
public function searchNameAttr($query, $value)
{
    $query->whereLike('name', trim($value));
}
```

**调用搜索器**

```php
// 自动获取请求参数
$this->model->search()->paginate();

// 手动传入查询参数
$params = request()->param(['name']);
$this->model->search($params)->paginate();
```

### 字典数据获取器

**字典获取器说明**

当模型中需要将状态字段（如 `check_status`、`stock_status`）的数值转换为可读文本时，可以通过定义获取器方法来实现。获取器会自动将字段的数值映射为对应的字典文本，便于前端直接显示。

**核心函数**

项目提供了 `get_dict_map()` 函数用于从字典缓存中获取文本值：

```php
/**
 * 获取字典映射
 *
 * @param  string  $type      字典类型（如 'purchase_check_status'）
 * @param  mixed   $value     字典值（如 0, 1, 2）
 * @param  string  $pattern   没有数据时返回的默认值（默认 '-'）
 * @param  bool    $fullInfo  是否返回完整信息（默认 false）
 * @return mixed
 */
function get_dict_map($type, $value, $pattern = '-', $fullInfo = false)
```

**定义获取器**

在模型中定义获取器方法（以 `get` + 字段名 + `Attr` 形式命名）：

```php
<?php
namespace app\model\purchase;

use core\base\BaseModel;

class PurchaseOrder extends BaseModel
{
    // 定义需要自动追加到结果中的获取器字段
    protected $append = [
        'check_status_text',    // 审核状态文本
    ];

    /**
     * 获取审核状态文本
     *
     * @param  string  $value  获取器的原始值
     * @param  array   $data   当前模型的所有数据
     * @return string          字典文本值
     */
    public function getCheckStatusTextAttr($value, $data)
    {
        return get_dict_map('purchase_check_status', $data['check_status']);
    }
}
```

**获取器命名规范**

- 方法名格式：`get` + 字段名（PascalCase）+ `Attr`
- 例如：字段名 `check_status` → 方法名 `getCheckStatusTextAttr`

**$append 属性说明**

- 用于声明哪些获取器的返回值需要自动追加到模型数据中
- 当查询数据时，这些字段会自动包含在返回的数据中
- 前端可以直接使用 `record.check_status_text` 获取文本值

**前端使用示例**

后端返回的数据会自动包含获取器的文本值：

```json
{
  "id": 1,
  "check_status": 1,
  "check_status_text": "已审核",
}
```

前端直接使用文本值：

```vue
<template>
  <a-table :dataSource="dataSource">
    <a-table-column title="审核状态" dataIndex="check_status_text">
      <template #default="{ record }">
        {{ record.check_status_text }}
      </template>
    </a-table-column>
  </a-table>
</template>
```

**注意事项**

1. 获取器方法名必须遵循 ThinkPHP 的命名规范
2. `$append` 属性确保获取器返回值会被自动追加到模型数据中
3. `get_dict_map()` 函数直接返回字典的文本值（name 字段）

## 服务层开发规范

### 服务层基类

继承 `core\base\BaseService`

```php
<?php
namespace app\service\system;

use core\base\Base\BaseService;
use app\model\system\User;

class UserService extends BaseService
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new User();
    }

    public function getList()
    {
        return $this->model->search()->paginate();
    }

    public function save($data)
    {
        return $this->model->storeBy($data);
    }

    public function update($id, $data)
    {
        return $this->model->updateBy($id, $data);
    }

    public function delete($id)
    {
        return $this->model->deleteBy($id);
    }
}
```

## 验证器开发规范

### 验证器基类

继承 `core\base\BaseValidate`

```php
<?php
namespace app\validate\system;

use core\base\BaseValidate;

class User extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:50',
        'email' => 'email',
        'mobile' => 'mobile'
    ];

    protected $message = [
        'name.require' => '姓名不能为空',
        'name.max' => '姓名最多50个字符',
        'email.email' => '邮箱格式不正确',
        'mobile.mobile' => '手机号格式不正确'
    ];
}
```

## 路由配置

### 资源路由

```php
<?php
use think\facade\Route;



// 路由组添加中间件
Route::group(function () {
    Route::resource('user', 'user');
    Route::delete('system/department', 'system.department/delete');
    // 资源路由，自动生成7个路由规则
    Route::group(function () {
        Route::resource('user', 'user');
    });
})->middleware('auth'); // auth中间件包含JWT认证+权限检查
```

### 资源路由规则

| 标识     | 请求类型   | 生成路由规则        | 对应操作方法 |
| ------ | ------ | ------------- | ------ |
| index  | GET    | user          | index  |
| create | GET    | user/create   | create |
| save   | POST   | user          | save   |
| read   | GET    | user/:id      | read   |
| edit   | GET    | user/:id/edit | edit   |
| update | PUT    | user/:id      | update |
| delete | DELETE | user/:id      | delete |

## 登录认证（JWT）

### JWT使用

```php
use core\service\jwt\Factory;

// 生成令牌
$jwt = Factory::getInstance();
$tokens = $jwt->generateToken(['id' => 1]);

// 指定场景生成令牌
$apiJwt = Factory::getInstance('api');
$apiTokens = $apiJwt->generateToken(['id' => 2]);

// 验证访问令牌（自动从请求头获取）
$jwt->verifyAccessToken();

// 验证刷新令牌
$refreshToken = request()->params('refreshToken');
$jwt->verifyRefreshToken($refreshToken);

// 刷新令牌
$token = $jwt->refreshToken();
```

### 获取登录信息

```php
// 获取当前登录用户信息
request()->user();

// 获取当前登录用户id
request()->uid();
```

### JWT配置

配置文件：`config/jwt.php`

```php
<?php
use Lcobucci\JWT\Signer\Hmac\Sha256;

return [
    'default' => [
        'key' => 'id',              // 用户ID字段名
        'secret' => 'xxxxxxxx',     // 加密密钥
        'ttl' => 3600,              // accessToken有效期(秒)
        'refresh_ttl' => 7200,      // refreshToken有效期
        'cache_prefix' => 'speed_jwt', // 缓存前缀
        'blacklist_ttl' => 7200,   // 黑名单有效期
        'alg' => new Sha256(),      // 签名算法
    ],
];
```

## 权限控制（RBAC）

### 接口权限控制

1. 在菜单管理中添加权限节点（模块:控制器方法形式）
2. 在路由中使用 `auth` 中间件

```php
Route::group(function () {
    Route::delete('system/department', 'system.department/delete');
})->middleware('auth');
```

### 数据权限

**使用数据权限前确保**：

- 表中拥有与用户表id相关联的字段（如user\_id）
- 已在角色上设置数据权限范围
- 使用部门权限时，用户已设置所属部门

**模型中使用**：

```php
<?php
namespace app\model\system;

use core\base\BaseModel;
use core\traits\DataScope;

class Test extends BaseModel
{
    use DataScope;

    public function getList()
    {
        // 参数是表中关联的用户id字段
        return $this->dataRange('user_id')->select();
    }
}
```

**数据权限类型**：

- 全部数据权限
- 自定义数据权限
- 仅本人数据权限
- 部门数据权限
- 部门及以下数据权限

### 前端按钮权限

```vue
<template>
  <!-- 通过权限节点判断 -->
  <button v-auth="'system:department:delete'">删除</button>

  <!-- 通过角色判断 -->
  <button v-role="'admin'">编辑</button>
</template>
```

## 自定义Query类

`BasisQuery` 继承框架 Query 类，新增方法：

```php
// 搜索器
public function search(array $params){}

// 模糊查询 %like%
public function whereLike(string $field){}

// 模糊查询 %like
public function whereLeftLike(string $field){}

// 模糊查询 like%
public function whereRightLike(string $field){}

// 重写分页
public function paginate($listRows = null, $simple = false)
```

## 自定义数据集对象

`ModelCollection` 继承框架 Collection 类，新增方法：

```php
// 将数据集转为树形结构
public function toTree(int $pid = 0, string $pidField = 'pid'){}

// 导出数据
public function export($column, string $extension = 'csv'){}

// 获取当前级别下的所有子级
public function getAllChildrenIds(array $ids, string $parentFields = 'parent_id', string $column = 'id'){}
```

## 文件上传

### 上传配置

配置文件：`config/filesystem.php`

```php
return [
    // 默认磁盘: oss、local、qiniu、qcloud
    'default' => env('filesystem.driver', 'oss'),
    'folder'  => 'a',

    'disks' => [
        'local' => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
            'domain' => env('upload.url', app()->getRuntimePath() . 'storage/')
        ],
        'oss' => [
            'type' => 'aliyun',
            'accessId' => '******',
            'accessSecret' => '******',
            'bucket' => 'bucket',
            'endpoint' => 'endpoint',
            'url' => '' // 域名地址，不要斜杠结尾
        ],
        'qiniu' => [
            'type' => 'qiniu',
            'accessKey' => '******',
            'secretKey' => '******',
            'bucket' => 'bucket',
            'url' => '' // 域名地址，不要斜杠结尾
        ],
        'qcloud' => [
            'type' => 'qcloud',
            'region' => '***',
            'appId' => '***',
            'secretId' => '***',
            'secretKey' => '***',
            'bucket' => '***',
            'timeout' => 60,
            'connect_timeout' => 60,
            'cdn' => '您的 CDN 域名',
            'scheme' => 'https',
            'read_from_cdn' => false,
        ]
    ]
];
```

### 上传使用

```php
use core\service\upload\UploadService;

// 实例化上传类
$upload = new UploadService();

// 上传图片（在config/system.php配置文件中的upload.image自定义允许上传的图片类型）
$upload->checkImages()->upload($file);

// 上传文件（在config/system配置文件的upload.file自定义允许上传的文件类型）
$upload->checkFiles()->upload($file);

// 自定义验证
$upload->validate('fileSize:1024000|fileExt:zip,rar,7z,tar')->upload($file);
```

## HTTP请求工具

`core\utils\Http` 提供 HTTP 请求工具

```php
use core\utils\Http;

// get请求
Http::get($url, $query = [], $options = []);

// post请求
Http::post($url, $data = [], $options = []);
```

## 命名规范

| 类型   | 规范          | 示例                    |
| ---- | ----------- | --------------------- |
| 控制器  | PascalCase  | UserController.php    |
| 模型   | PascalCase  | User.php              |
| 服务层  | PascalCase  | UserService.php       |
| 验证器  | PascalCase  | UserValidate.php      |
| 方法   | camelCase   | getList(), saveUser() |
| 数据库表 | snake\_case | eb\_user              |

## 开发注意事项

1. **所有类必须包含清晰的文档注释**（PHPDoc）
2. **方法内部关键逻辑必须添加注释**
3. **注释全部使用中文**
4. **不要格式化代码**
5. **控制器中业务逻辑应在服务层实现**
6. **模型中使用搜索器处理查询条件**
7. **资源路由方法命名遵循规范**
8. **权限节点格式：模块:控制器:方法**





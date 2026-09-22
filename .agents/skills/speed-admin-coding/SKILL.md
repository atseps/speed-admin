---
name: speed-admin-coding
description: SpeedAdmin 项目编码规范（ThinkPHP 8 后端 + Vue 3 前端）。在 speed-admin 项目中新增/修改/生成前后端代码、CRUD 功能，或使用 STable、SForm、SModalForm、useTable、useModal 等核心组件时使用此技能。包含分层结构、命名规范、资源路由、JWT/RBAC、核心组件用法约定。
---

## 技术栈

- 前端：Vue 3 + TypeScript + Vite + Ant Design Vue + Pinia（位于 `web/` 目录）
- 后端：PHP 8.0+ + ThinkPHP 8.1 + ThinkORM，MySQL
- 路由前缀：`/adminapi`（后台管理接口）、`/api`（前台接口），两套应用区分
- 表前缀：`eb_`（snake_case）

## 常用命令

前端（web/ 目录）：

```bash
npm install          # 安装依赖
npm run dev          # 开发服务器
npm run build        # 生产构建
npm run preview      # 预览构建结果
npm run format       # Prettier 格式化
npm run lint         # ESLint 检查并自动修复
npx eslint web/src/components/Table/STable.vue --fix  # 检查单个文件
```

后端（根目录）：

```bash
composer install         # 安装 PHP 依赖
php think <command>      # 运行 ThinkPHP 命令
```

## 后端核心约定

1. **分层顺序**：Model → Validate → Service → Controller → 路由；业务逻辑放 Service 层，控制器不写业务。
2. **控制器**继承 `core\base\BaseController`，构造函数注入 Service；响应用 `$this->success($data)` / `$this->error($msg)`，业务异常抛 `core\exception\FailedException`。
3. **模型**继承 `core\base\BaseModel`；查询条件用搜索器（`searchXxxAttr`），Service 中 `$this->model->search()->paginate()` 自动读取请求参数。
4. **字典值转换文本**：模型定义获取器 `getXxxTextAttr` + `get_dict_map('字典类型', $值)`，通过 `$append` 注入 `{field}_text`，前端直接用 `record.check_status_text`。
5. **BaseModelTrait 数据操作方法**：`storeBy`（新增）、`createBy`（循环插入用）、`updateBy($id,$data)`、`findBy($id,$field,$trash)`、`deleteBy($id,$force)`、`insertAllBy`（批量插入）、`recover`（软删恢复）、`disOrEnable`（启停用，默认 status 字段）。
6. **服务层**继承 `core\base\BaseService`；**验证器**继承 `core\base\BaseValidate`。
7. **资源路由**：`Route::resource` + `auth` 中间件（JWT 认证 + RBAC 权限检查），7 个标准动作：index/create/save/read/edit/update/delete。
8. **数据权限**：模型 `use core\traits\DataScope`，查询时 `$this->dataRange('user_id')`（参数为关联用户 id 的字段）。
9. **JWT**：`core\service\jwt\Factory::getInstance()` 生成/验证/刷新令牌；`request()->user()`、`request()->uid()` 获取登录信息；配置在 `config/jwt.php`。
10. **常用工具**：上传用 `core\service\upload\UploadService`（配置在 `config/filesystem.php`）；HTTP 请求用 `core\utils\Http::get()/post()`。

## 前端核心约定

1. **自动导入**（无需手动 import）：Vue API（ref/computed/watch 等）、Vue Router、Ant Design Vue 组件（a-button 等）、`src/components/` 下公共组件、`src/hooks/web/` 组合式函数；`@/` 别名指向 `web/src/`。
2. **自定义组件**用 `S` 前缀（STable、SForm、SModalForm、SDescription、SModal、SDrawer、SUpload）；优先使用组合式函数（useTable、useModal 等）。
3. **列表页**：`STable` + `useTable`；搜索表单 `searchForm` 配置**必须用 `component` 属性指定组件类型**（不是 type），组件配置参数放 `props` 对象中；支持 Input/Select/TreeSelect/DatePicker/RangePicker/TableSelect 等。
4. **弹窗表单**：`SModalForm`（saveApi/readApi/model/rules）+ `useModal`/`useModalInner`；表单项用 s-input/s-select/s-date-picker 等包裹。
5. **API 定义**严格遵循后端资源路由命名：index、create、save（有 id 走 PUT 无 id 走 POST）、read、getEdit、del；批量删除 DELETE 并传 ids 数组。
6. **v-model 统一语法**，不使用 `v-model:value`。
7. **暗黑主题**：优先用 CSS 变量（`--ant-color-bg-container`、`--ant-color-text` 等），或 `[data-theme="dark"]` 选择器、Tailwind `dark:` 前缀。
8. 文件名 kebab-case（user-form.vue），组件引用 PascalCase（`<UserForm />`）。

## 命名规范

| 类型 | 规范 | 示例 |
| --- | --- | --- |
| 控制器/模型/服务/验证器 | PascalCase | Product.php / ProductService.php |
| 方法 | camelCase | getList() |
| 数据库表 | snake_case | eb_product |
| 前端文件 | kebab-case | user-form.vue |
| API 方法 | 资源路由命名 | index / save / read / del |

## 详细参考（按需加载，不要全部读入）

- `references/frontend.md` — STable 搜索表单、useTable/SForm/SModalForm/SDescription/SModal/SDrawer/SUpload 完整用法与正误示例、API 定义完整代码、暗黑主题变量。
- `references/backend.md` — 控制器/模型/服务/验证器完整代码示例、搜索器与字典获取器、资源路由规则表、JWT 配置与用法、数据权限、上传配置、BasisQuery/ModelCollection 扩展方法。

## 交互要求

1. 输出的所有回答和思考过程必须全部使用中文，不能使用英文，代码语法英文关键词除外。
2. 不要格式化代码，保持原始代码格式。
3. 所有方法/函数必须包含清晰的文档注释（如 PHPDoc 或 JSDoc），说明功能、参数和返回值，Vue的模版代码不需要注释；
   方法内部的关键逻辑、复杂表达式或非显而易见的操作，必须添加行内注释或块注释，解释“为什么这么做”或“这一步在做什么”；
   注释应简洁、准确，避免冗余，用中文书写；
   优先保证代码可读性和可维护性。

# Speed Admin - Agent Coding Guidelines

- **前端**: Vue 3 + TypeScript + Vite + Ant Design Vue + Pinia（web/ 目录）
- **后端**: PHP 8.0+ + ThinkPHP 8.1 + ThinkORM，MySQL
- **路由前缀**: /adminapi（后台管理接口）、/api（前台接口）
- **数据库表**: snake_case，前缀 eb_

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
php think run            # 开发服务器 
```

### 运行单个 ESLint 检查

```bash
npx eslint web/src/components/Table/STable.vue --fix  # 检查单个文件
npx eslint web/src/views/user --fix                   # 检查单个目录
```

## 编码规范

涉及以下场景时，先读取该技能的 `SKILL.md`（核心约定），详细代码示例按需查阅其 `references/` 目录：

- 新增/修改/生成前后端代码或 CRUD 功能（分层顺序：Model → Validate → Service → Controller → 路由）
- 使用 STable、SForm、SModalForm、SModal、SDrawer、SUpload 等核心组件（useTable/useModal 等）
- 定义前后端 API（必须遵循资源路由规范：index/create/save/read/edit/update/delete）
- JWT 认证、RBAC 权限、数据权限、文件上传等基础设施

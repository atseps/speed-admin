# Vue 3 + Ant Design Vue Admin

一个现代化的企业级后台管理系统模板，基于 Vue 3、TypeScript、Vite 和 Ant Design Vue 4。

## 特性

- 🎯 基于 Vue 3、Vite、TypeScript 等最新技术栈
- 🎨 使用 Ant Design Vue 4.x 组件库
- 📦 使用 Composition API 和 `<script setup>` 语法糖
- 🔑 内置完善的权限管理方案
- 🎭 支持多主题和暗色模式
- 📱  支持多布局和个性化配置
- 🚀 基于 Vite 构建,开发体验极致

## 技术栈

- 核心框架：Vue 3.x
- UI 框架：Ant Design Vue 4.x
- 构建工具：Vite 5.x
- 编程语言：TypeScript 5.x
- 状态管理：Pinia
- HTTP 工具：Axios
- 路由：Vue Router 4.x

## 项目结构

``` js
├─📂 public //公共静态资源目录
├─📂 src    //主目录
│  ├─📂 api        //接口文件
│  ├─📂 assets     //资源文件
│  ├─📂 components //公共组件
│  ├─📂 config    //项目配置
│  ├─📂 directives  //指令
│  ├─📂 hooks  //组合式函数
│  ├─📂 layout  //布局
│  ├─📂 router  //路由
│  ├─📂 store  //状态管理
│  ├─📂 styles  //样式
│  ├─📂 utils  //工具类
│  ├─📂 views  //页面
│  ├─📄 main.ts  //主入口
├─📂 types  //类型文件
├─📄 .env.development // 环境变量文件（开发环境）
├─📄 .env.production // 环境变量文件（生产环境）
├─📄 vite.config.ts // vite配置文件
```
## 功能模块

### 1. 布局系统
- 支持四种布局模式:
  - 侧边栏模式
  - 顶部导航模式
  - 混合模式
  - 双列导航模式
- 可配置的主题
- 可固定头部和侧边栏
- 响应式设计

### 2. 权限系统
- 基于角色的权限控制
- 菜单权限
- 按钮权限

### 3. 组件封装
- 表格组件
- 表单组件
- 详情组件
- 树形选择器
- 上传组件
- 弹窗组件
- 表格选择器

## 快速开始

### 环境准备

- Node.js 18+、20+ 和 22+

### 安装依赖

```bash
npm install
```

### 开发环境运行
```bash
npm run dev
```
## 项目配置

### 环境变量配置
开发环境
.env.development

生产环境
.env.production
### 主题配置

可以在 `src/config/app.ts` 中修改主题相关配置:
``` typescript
export default {
 primaryColor: "#4073fa", // 主色系
 theme: "light", // 菜单主题
// ...
}
```
## 许可证

[MIT](LICENSE)


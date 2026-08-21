<br />
<div align="center">
    <h1 style="font-size: 36px;color: #2c3e50;font-weight: 600;margin: 0 0 6px 0;">SpeedAdmin</h1>
    <p style="font-size: 17px;color: #6a8bad;margin-bottom: 10px;">基于ThinkPHP8 + Vue3开发的通用后台管理系统，支持RBAC权限管理、代码生成器等特性。</p>
    <p>
        <a href="https://atsep.top/web" target="_blank">演示</a> |
        <a href="https://atsep.top/docs" target="_blank">文档</a> |
        <a href="https://gitee.com/fantasyc/speed-admin" target="_blank">Gitee仓库</a> |
        <a href="https://github.com/atseps/speed-admin" target="_blank">GitHub仓库</a>
    </p>
    <p align="center">
      <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8-8892bf"></a>
      <a href="https://www.tslang.cn/"><img src="https://img.shields.io/badge/TypeScript-4-294e80"></a>
      <a href="https://www.thinkphp.cn/"><img src="https://img.shields.io/badge/ThinkPHP-8-6fb737"></a>
      <a href="https://cn.vuejs.org/"><img src="https://img.shields.io/badge/Vue.js-3-4eb883"></a>
      <a href="https://cn.vitejs.dev/"><img src="https://img.shields.io/badge/vite-5-ffc018"></a>
      <a href="https://www.antdv.com/components/overview-cn"><img src="https://img.shields.io/badge/Antd Design Vue-4.2-409eff"></a>
    </p>
    <img src="/public/readme/home.png" alt="" />
</div>
<br />


## 访问

体验地址：[https://atsep.top/web](https://atsep.top/web) <br>
账号：demo 密码：123456<br>
文档地址：[https://atsep.top/docs](https://atsep.top/docs)


#### 基于SpeedAdmin开发的CRM
项目地址：[https://gitee.com/fantasyc/speed-crm](https://gitee.com/fantasyc/speed-crm) <br>
体验地址：[https://crm.atsep.top/web](https://crm.atsep.top/web) <br>
账号：demo 密码：123456<br>


## 🚀 快速开始

### 环境要求

- **PHP**: >= 8.2
- **MySQL**: >= 5.7
- **Node.js**: ^22.18.0 || >=24.11.0


### 后端安装
1. 拉取代码 
```sh
git clone https://gitee.com/fantasyc/speed-admin.git
```

2. 安装依赖 

```sh
composer install
```
3. 初始化数据

```sh
# 执行之前请先配置好你的数据库连接信息
php think install:database
```
4. 启动 
```sh
php think run
```

### 前端安装

1. **进入前端目录**
```bash
cd web
```

2. **安装依赖**
```bash
npm install
```
#### 配置接口地址
在启动前需要先配置接口地址，在前端项目根目录下的 `.env.development` 文件中，设置 `VITE_API_BASE_URL` 变量，指定开发环境的 API 请求地址


```js
#请求api
VITE_API_BASE_URL='http://127.0.0.1:8000/adminapi' #你的实际接口地址

```
3. 启动 
```sh
npm run dev
```


## 特性

### 🔥 现代化全栈开发技术栈

#### TP8 + Vue3 + TypeScript + Ant Design Vue

基于最新技术生态构建，提供高效、稳定、类型安全的开发体验。

---

#### 🔐 安全与身份认证

**JWT 身份认证：** 基于 Token 的无状态鉴权，保障系统安全。

**Token 无感刷新：** 自动续签 Token，提升用户体验。

**RBAC 权限控制：** 基于角色的访问控制，精细化权限管理（菜单、按钮、API）。

---

#### 📊 系统功能

**✅ 操作日志记录：** 完整追踪用户行为，支持操作审计。

**🚀 代码生成器：** 一键生成 CRUD 代码，提升开发效率。

**🎨 多主题 & 暗黑模式：** 支持动态切换主题，适配不同场景需求。

**⚙️ 个性化配置：** 支持界面布局、路由动画灵活配置。

---

#### 🛠 技术特色

**前后端分离架构：** 职责清晰，便于协作与维护。

**TypeScript 支持：** 前端类型安全，减少运行时错误。

**Ant Design Vue：** 企业级 UI 组件库，开箱即用，风格统一。

**高效开发体验：** 丰富的前端组件 + 一键生成 CRUD 代码，提升开发效率。

## 页面预览

  <img src="/public/readme/menu.png" alt="" />
  <img src="/public/readme/code.png" alt="" />
  <img src="/public/readme/dict.png" alt="" />
  <img src="/public/readme/auth.png" alt="" />
  <img src="/public/readme/setting.png" alt="" />
  <img src="/public/readme/operation.png" alt="" />


## 许可证

本项目基于 [MIT](LICENSE) 协议开源。

## 联系方式

- 问题反馈: [Issues](https://gitee.com/fantasyc/speed-admin/issues)
- 邮箱: [atsep@qq.com](atsep@qq.com)

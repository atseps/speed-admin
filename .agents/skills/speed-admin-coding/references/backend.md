# SpeedAdmin 后端开发详细规范

> 本文件是 `speed-admin-coding` 技能的详细参考，编写后端 PHP 代码时按需查阅。

## 技术栈

- PHP 8.0+
- ThinkPHP 8.1
- MySQL（ThinkORM）
- JWT（JSON Web Token）
- RBAC（基于角色的访问控制）

## 目录结构

```
├─ app                 # 后端根目录
│  ├─ adminapi         # adminapi应用目录（后台管理接口）
│  ├─ api              # api应用目录
│  ├─ model            # 模型目录
│  ├─ service          # 服务层目录（业务逻辑）
├─ config              # 配置目录
├─ core                # 核心扩展目录（公共类库封装）
├─ router              # 路由文件夹
├─ public              # WEB目录（对外访问目录）
│  │  ├─ index.php     # php入口文件
├─ .env                # 项目环境配置文件
```

**core 核心扩展目录**：

```
├─ core
│  ├─ base            # 基类（BaseController/BaseModel/BaseService/BaseValidate）
│  ├─ facade          # 门面
│  ├─ service         # 服务类（jwt/upload 等）
│  ├─ traits          # 组合（DataScope 等）
│  ├─ utils           # 工具（Http 等）
│  ├─ AppService.php  # 应用服务类
│  ├─ BasisQuery.php  # db查询类
│  ├─ ModelCollection.php  # 模型集合类
│  └─ Permissions.php  # 权限认证类
```

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

当模型中需要将状态字段（如 `check_status`、`stock_status`）的数值转换为可读文本时，通过定义获取器实现，获取器会自动将字段的数值映射为对应的字典文本，便于前端直接显示。

**核心函数**：项目提供 `get_dict_map()` 函数用于从字典缓存中获取文本值：

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

**定义获取器**（以 `get` + 字段名 + `Attr` 形式命名）：

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

**获取器命名规范**：方法名格式 `get` + 字段名（PascalCase）+ `Attr`。例如字段名 `check_status` → 方法名 `getCheckStatusTextAttr`。

**$append 属性说明**：声明哪些获取器的返回值需要自动追加到模型数据中；查询数据时这些字段会自动包含在返回数据中，前端可直接使用 `record.check_status_text` 获取文本值。

**前端使用示例**：

后端返回的数据会自动包含获取器的文本值：

```json
{
  "id": 1,
  "check_status": 1,
  "check_status_text": "已审核"
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

**注意事项**：

1. 获取器方法名必须遵循 ThinkPHP 的命名规范
2. `$append` 属性确保获取器返回值会被自动追加到模型数据中
3. `get_dict_map()` 函数直接返回字典的文本值（name 字段）

## 服务层开发规范

服务层基类继承 `core\base\BaseService`

```php
<?php
namespace app\service\system;

use core\base\BaseService;
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

验证器基类继承 `core\base\BaseValidate`

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

// 路由组添加中间件（auth中间件包含JWT认证+权限检查）
Route::group(function () {
    Route::resource('user', 'user');
    Route::delete('system/department', 'system.department/delete');
})->middleware('auth');
```

### 资源路由规则

| 标识 | 请求类型 | 生成路由规则 | 对应操作方法 |
| --- | --- | --- | --- |
| index | GET | user | index |
| create | GET | user/create | create |
| save | POST | user | save |
| read | GET | user/:id | read |
| edit | GET | user/:id/edit | edit |
| update | PUT | user/:id | update |
| delete | DELETE | user/:id | delete |

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

- 表中拥有与用户表id相关联的字段（如user_id）
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

**数据权限类型**：全部数据权限、自定义数据权限、仅本人数据权限、部门数据权限、部门及以下数据权限。

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

| 类型 | 规范 | 示例 |
| --- | --- | --- |
| 控制器 | PascalCase | UserController.php |
| 模型 | PascalCase | User.php |
| 服务层 | PascalCase | UserService.php |
| 验证器 | PascalCase | UserValidate.php |
| 方法 | camelCase | getList(), saveUser() |
| 数据库表 | snake_case | eb_user |

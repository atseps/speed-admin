<?php

namespace app;

use app\service\user\AuthService;
use Spatie\Macroable\Macroable;
use app\model\system\User;
use core\exception\FailedException;

// 应用请求对象类
class Request extends \think\Request
{
    use Macroable;

    protected $filter = ['htmlspecialchars', 'trim'];

    /**
     * 当前登录的后台用户
     *
     * @return user
     */
    public function user()
    {
        try {
            $user = app()->make(AuthService::class)->user();
        } catch (\Exception $e) {
              throw new FailedException($e->getMessage(), httpCode:401);
        }
        return $user;
    }
}

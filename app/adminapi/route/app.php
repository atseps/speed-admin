<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use think\middleware\Throttle;
//登录模块
Route::group(function () {
    //账号登录
    Route::post('login', 'login.Index/login');
    //退出登录
    Route::post('logout', 'login.Index/logout');
    //刷新令牌
    Route::get('refreshToken', 'login.Index/refreshToken');
});
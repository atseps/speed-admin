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

//系统模块
Route::group(function () {
    //获取用户信息
    Route::get('getUserInfo', 'system.user/getUserInfo');
    //获取路由
    Route::get('getRouter', 'system.menu/getRouter');

    //登录日志
    Route::group('login_log', function () {
        //清空登录日志
        Route::delete('clear', 'system.login_log/clear');
        //删除登录日志
        Route::post('delete', 'system.login_log/delete');
        //导出登录日志
        Route::post('export', 'system.login_log/export');
    });

    //操作日志
    Route::group('operate_log', function () {
        //清空操作日志
        Route::delete('clear', 'system.operate_log/clear');
        //删除操作日志
        Route::post('delete', 'system.operate_log/delete');
    });

    //字典
    Route::group('dict', function () {
        //更新字典缓存
        Route::post('updateCache', 'system.dict/updateCache');
        //获取字典
        Route::get('get', 'system.dict/get');
        //更新排序
        Route::post('updateSort', 'system.dict/updateSort');
        //更改状态
        Route::post('changeStatus/:id', 'system.dict/changeStatus');
    });

    //代码生成器
    Route::group('generator', function () {
        //获取所有的数据表
        Route::get('getAllTable', 'system.generator/getAllTable');
        //生成代码
        Route::post('makeCode/:id', 'system.generator/makeCode');
        //预览代码
        Route::get('preview/:id', 'system.generator/preview');
        //删除字段
        Route::delete('deleteFiled/:id', 'system.generator/deleteFiled');
        //下载
        Route::get('download', 'system.generator/download')->withoutMiddleware();
    });

    //用户
    Route::group('user', function () {
        //获取激活的用户
        Route::get('getActiveUsers', 'system.user/getActiveUsers');
        //修改用户状态
        Route::put('changeStatus/:id', 'system.user/changeStatus');
        //重置密码
        Route::put('resetPassword/:id', 'system.user/resetPassword');
        //修改密码
        Route::put('changePassword', 'system.user/changePassword');
        //根据id获取用户
        Route::get('getUserById', 'system.user/getUserById');
        //更新用户信息
        Route::put('updateInfo', 'system.user/updateInfo');
    });

    //角色
    Route::group('role', function () {
        //获取全部角色
        Route::get('all', 'system.role/all');
    });

    //上传
    Route::group('upload', function () {
        //文件上传
        Route::post('file', 'system.file/uploadFile');
        //图片上传
        Route::post('image', 'system.file/uploadImg');
        //附件上传
        Route::post('attachment', 'system.file/uploadAttachment');
    });


    //资源路由
    Route::group(function () {
        //用户
        Route::resource('user', 'system.user');
        //权限
        Route::resource('authAccess', 'system.authAccess')->only(['index', 'save']);
        //角色
        Route::resource('role', 'system.role');
        //部门
        Route::resource('department', 'system.department');
        //字典
        Route::resource('dict', 'system.dict');
        //菜单
        Route::resource('menu', 'system.menu');
        //操作日志
        Route::resource('operate_log', 'system.OperateLog');
        //登录日志
        Route::resource('login_log', 'system.LoginLog');
        //代码生成器
        Route::resource('generator', 'system.generator');
    });
})->middleware('auth');

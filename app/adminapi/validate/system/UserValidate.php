<?php

namespace app\adminapi\validate\system;

use core\base\BaseValidate;
use app\model\system\User;
class UserValidate extends BaseValidate
{
    protected $rule = [
        'id' =>'require|checkAdmin',
        'username' =>  'require|max:30|unique:user|alphaNum',
        'roles'  =>  'require|array',
        'realname' => 'require|max:10',
        'dept_id' => 'require',
        'phone' => 'mobile|unique:user',
        'email' => 'email|unique:user'
    ];


    public $field = [
        'realname' => '姓名',
        'roles' => '角色',
        'username' => '用户名',
        'dept_id' => '部门',
        'email' => '邮箱',
        'phone' => '手机号码'
    ];
    

    // 添加验证场景定义
    public function sceneAdd()
    {
    	return $this->remove('id',['require','checkAdmin']);
    }   

    // 编辑验证场景定义
    public function sceneEdit()
    {
    	return $this->only(['id', 'realname', 'roles', 'dept_id', 'phone', 'email']);
    }   

    // 更新个人信息验证场景定义
    public function sceneUpdateInfo()
    {
    	return $this->only(['realname', 'phone', 'email']);
    }   

   // 检查用户验证场景定义
    public function sceneCheckUser()
    {
    	return $this->only(['id']);
    }   

    /**
     *  验证用户
     */
    public function checkAdmin($value, $rule, $data)
    {
        $user = User::find($value);
        if (!$user) {
            return '用户不存在';
        }
        if ($user->is_admin) {
            return '超管不可修改';
        }
        return true;
    }
}

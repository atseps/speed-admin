<?php

namespace app\adminapi\validate\system;

use app\model\system\User;
use core\base\BaseValidate;

class PasswordValidate extends BaseValidate
{
    protected $rule = [
        'password_old' =>  'require|correct',
        'password' =>  'require|alphaPwd|notChs|confirm',
        'password_confirm' =>  'require',
    ];

    protected $message = [
        'password_old.require' => '原密码不能为空',
        'password_old.correct' => '原密码不正确',
        'password.confirm' => '两次输入密码不一致',
        'password.require' => '新密码不能为空',
        'password.alphaPwd' => '新密码必须包含数字和字母,且不小于6位',
        'password.notChs' => '新密码不能包含中文',
    ];

    /**
     * 验证密码是否正确
     * @param string $value 验证内容
     * @param string $rule 验证规则
     * @param $data
     * @param string $field 验证的字段名
     * @return  bool
     */
    public function correct($value, $rule, $data, $field)
    {
        $map['id'] = $data['id'];
        $user = User::where($map)->find();
        if (is_null($user) || !password_verify($value, $user->password)) {
            return false;
        }
        return true;
    }
}

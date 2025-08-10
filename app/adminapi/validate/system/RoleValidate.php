<?php

namespace app\adminapi\validate\system;

use core\base\BaseValidate;

class RoleValidate extends BaseValidate
{
    protected $rule = [
        'name'  =>  'require|unique:role|max:30',
        'note'  => 'max:100',
        'role_key' => 'require|unique:role|max:30',
        'departments'=>'requireIf:data_range,2',
        'data_range'=>'in:1,2,3,4,5'
    ];

    protected $message = [
        'name.require' => '角色名称必填',
        'note.max'=>'备注不能超过100个字符',    
        'role_key.max'=>'权限标识不能超过30个字符',
        'data_range.in'=>'数据范围参数值不正确',
        'name.unique' => '角色名称已存在',
        'role_key.unique' => '权限标识已存在',
    ];
}

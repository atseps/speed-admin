<?php

namespace app\adminapi\validate\system;

use core\base\BaseValidate;

class DepartmentValidate extends BaseValidate
{
    protected $rule = [
        'parent_id' =>  'require|checkParentId',
        'name'  =>  'require|max:30',
        'sort'  => 'number|between:1,9999',
        'leader_id' => 'string'
    ];

    protected $message = [
        'parent_id.require' => '上级部门不能为空',
        'name.require' => '名称不能为空',
        'name.max' => '名称不能超过30个字符',
        'sort.between' => '排序只能在1~9999之间'
    ];


     /**
     * 校验上级部门
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkParentId($value, $rule, $data)
    {
        if (!empty($data['id']) && $data['id'] == $value) {
            return '上级部门不能选择自己';
        }
        return true;
    }
}

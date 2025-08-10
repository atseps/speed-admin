<?php

namespace app\model\system;

use core\base\BaseModel;

class AuthAccess extends BaseModel
{

    /**
     * 获取对应角色的权限
     * @param  string $role_id  角色id
     * @return array
     */
    public static function getPermission($role_id)
    {
        return self::whereIn('role_id', $role_id)->column('menu_id');
    }
}

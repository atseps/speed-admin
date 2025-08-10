<?php

namespace app\model\system\traits;

use app\model\system\User;

trait RoleUserTrait
{

    /**
     * 角色用户多对多相对关联
     * 
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role', 'user_id', 'role_id');
    }


    /**
     * 关联查询用户数据
     * 
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users()->select();
    }
}

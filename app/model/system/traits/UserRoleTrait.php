<?php

namespace app\model\system\traits;

use app\model\system\Role;

trait UserRoleTrait
{
    /**
     * 用户角色多对多关联
     * 
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'role_id', 'user_id');
    }

    /**
     * 关联查询角色数据
     * 
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles()->select();
    }



    /**
     * 关联查询角色id
     * 
     * @return mixed
     */
    public function getRolesId()
    {
        return $this->getRoles()->column('id');
    }


    /**
     * 关联新增角色数据
     * 
     * @param array $roles
     * @return mixed
     */
    public function saveRoles(array $roles)
    {
        if (empty($roles)) {
            return true;
        }

        sort($roles);

        return $this->roles()->attach($roles);
    }


    /**
     * 关联更新角色数据
     * 
     * @param array $roles
     * @return boolean
     */
    public function updateRoles(array $roles)
    {

        $this->roles()->detach(); //删除关联数据
        $this->saveRoles($roles); //新增关联数据
        return true;
    }
}

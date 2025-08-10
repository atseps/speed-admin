<?php

namespace app\model\system\traits;

use app\model\system\Menu;

trait RoleMenuTrait
{
    /**
     * 菜单角色多对多关联
     * 
     * @return mixed
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'auth_access', 'menu_id', 'role_id');
    }


    /**
     * 关联查询菜单数据
     * 
     * @return mixed
     */
    public function getMenus()
    {
        return $this->menus()->where('type', '<>', 2)->sort('asc')->select();
    }
}

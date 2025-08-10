<?php

namespace app\service\system\permission;

use core\base\BaseService;
use app\model\system\{AuthAccess, Role, Menu};

class AuthAccessService extends BaseService
{


    /**
     * 获取列表
     * 
     * @param string $role_id
     * @return array
     */
    public function getList($role_id)
    {
        $authNode = MenuService::getRuleAll();
        $roleRule = AuthAccess::getPermission($role_id);
        return ['authNode' => $authNode, 'checked' => $roleRule];
    }


    /**
     * 保存
     * @param string $role_id 角色id
     * @param array $menu_ids 菜单id数组
     * @return bool
     */
    public function save($role_id, array $menu_ids): bool
    {
        try {
            $role = Role::find($role_id);
            // 开启事务
            $this->startTrans();
            // 删除关联的数据
            $role->menus()->detach();
            // 添加新关联
            if (!empty($menu_ids)) {
                $role->menus()->attach($menu_ids);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollBack();
            return false;
        }
    }


    /**
     * 创建
     * @param string $menu_id
     * @param string $role_id
     * @return int
     */
    public function create($menu_id, $role_id)
    {
        return AuthAccess::create(['menu_id' => $menu_id, 'role_id' => $role_id]);
    }


    /**
     * 删除
     * @param string $menu_id
     * @return bool
     */
    public function delete($menu_id)
    {
        return AuthAccess::where('menu_id', $menu_id)->delete();
    }
}

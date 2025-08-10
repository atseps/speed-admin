<?php

namespace core;

use think\facade\Db;


/**
 * 权限
 * @package core\Auth
 */
class Permissions {


    public $permissions_db = 'auth_access';
    public $user_role_db = 'user_role';

    /**
     * 检查权限
     * @param $name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $uid  int           认证用户的id
     * @param $relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($name, $uid, $relation = 'or') {
        //用户id为空认证失败
        if (empty($uid)) return false;

        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                //检查权限节点是否在权限表
                $findAuthRuleCount = Db::name('menu')->where(['rules' => $name])->count();
                if ($findAuthRuleCount == 0) { //没有规则时,不验证!
                    return true;
                }
                $name = [$name];
            }
        }

        $list  = []; //保存验证通过的规则名

        $roles = $this->getRoles($uid);

        //用户不属于任何角色认证失败
        if (empty($roles)) return false;

        //查询权限节点
        $rules = Db::name($this->permissions_db)
            ->alias("a")
            ->join('menu b', 'a.menu_id = b.id')
            ->whereIn('a.role_id', $roles)
            ->whereIn('b.rules', $name)
            ->select();

        foreach ($rules as $rule) {
            $list[] = strtolower($rule['rules']);
        }

        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }

        return false;
    }


    /**
     * 根据用户id获取角色,返回值为角色id
     * @param  int $uid      用户id
     * @return array        用户所属的用户组
     */
    private function getRoles($uid) {
        return Db::name($this->user_role_db)->where('user_id', $uid)->column('role_id');
    }
}

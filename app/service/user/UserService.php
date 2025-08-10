<?php

namespace app\service\user;

use core\base\BaseService;
use app\model\system\{User, AuthAccess, menu};

class UserService extends BaseService
{

    public function __construct(User $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $data = $this->model->search()->withoutField('password')->order('id', 'desc')->with(['roles', 'department'])->paginate();
        return $data;
    }


    /**
     * 获取激活的用户
     * @param array $data
     * @return array
     */
    public function getActiveUsers()
    {
        return $this->model->search()->where('status', 1)->field('id,realname,dept_id,avatar')->with(['department'])->cache(60)->paginate();
    }


    /**
     * 获取用户信息
     * @param  $id    用户id
     * @return array
     */
    public function getUserInfo()
    {
        $id = request()->uid();
        $user = $this->model->with(['department'])->withoutField('password,create_time,status,pinyin')->find($id);
        $role = $user->getRoles();
        $user->roles = $role->column('id');
        $user->role_name = $role->column('name');
        $user->rules = $this->getRules($role->column('id'));
        $user->avatar = $user->avatar ?: config('system.default_avatar');
        return $user;
    }


    /**
     * 获取角色的权限
     * @param  $roles    角色id
     * @return array
     */
    private function getRules(array $roles)
    {
        $menu_id = AuthAccess::getPermission($roles);
        $data = menu::where('type', 2)->whereIn('id', $menu_id)->sort('asc')->column('rules');
        return $data;
    }


    /**
     * 根据id获取用户
     * @param  $id
     * @return array
     */
    public function getUserById(array $ids)
    {
        return $this->model->whereIn('id', $ids)->field('id,realname')->cache(60)->select();
    }


    /**
     * 保存
     * @param array $data
     * @return bool
     */
    public function save(array $data)
    {
        //开启事务
        $this->startTrans();
        try {
            $data['password'] = config('system.def_password');
            $this->model->storeBy($data);
            //关联保存角色数据
            $this->model->saveRoles($data['roles']);
            $this->commit();  //提交事务
        } catch (\Exception $e) {
            $this->rollback();  //回滚事务
            return false;
        }
        return true;
    }


    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        $user = $this->model->withoutField(['password'])->findOrFail($id);
        $user->roles = $user->getRolesId();
        return $user;
    }




    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $this->startTrans();
        try {
            $this->model->updateBy($id, $data);
            //关联更新角色的数据
            $this->model->find($id)->updateRoles($data['roles']);
            $this->commit();  //提交事务
            return true;
        } catch (\Exception $e) {
            $this->rollback();  //回滚事务
            return false;
        }
    }


    /**
     * 修改状态
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function changeStatus($id)
    {
        return $this->model->disOrEnable($id);
    }


    /**
     * 修改密码
     * @param int $id
     * @param string $password
     * @return mixed
     */
    public function changePassword($data)
    {
        return $this->model->updateBy($data['id'], ['password' => $data['password']]);
    }



    /**
     * 更新个人信息
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateInfo($id, $data)
    {
        return $this->model->updateBy($id, $data);
    }




    /**
     * 重置密码
     *
     * @return \think\Response
     */
    public function resetPassword($id)
    {
        $password = config('system.def_password');
        $result = $this->model->updateBy($id, ['password' => $password]);
        return $result ? ['password' => $password] : false;
    }
}

<?php

namespace app\service\system\permission;

use core\base\BaseService;
use app\model\system\Role;
use core\exception\FailedException;

class RoleService extends BaseService
{


    public function __construct(Role $model)
    {
        $this->model = $model;
    }


    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        return $this->model->search()->paginate();
    }


    /**
     * 获取所有列表
     * @return array
     */
    public function getAll()
    {
        return $this->model->field('id,name')->select();
    }

    /**
     * 保存
     * @param array $data
     * @return int
     */
    public function save(array $data)
    {
        $id = $this->model->storeBy($data);
        if ($data['data_range'] == 2) {
           $this->model->saveDepartments($data['departments']);
        }
        return $id;
    }


    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        $result = $this->model->updateBy($id, $data);
        $this->model->find($id)->updateDepartments($data['departments']);
        return $result;
    }



    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id)
    {
        $role = $this->model->findOrFail($id);
        $role->departments = $role->getDepartmentId();
        return $role;
    }


    /**
     * 删除
     * @param  $id  id主键
     * @return bool
     */
    public function delete($id)
    {
        // 超级管理员不能删除
        if ($id == config('system.super_admin_id')) {
            throw new FailedException('超级管理员不能删除');
        }
        $role = $this->model->find($id);
        if (!$role) {
            throw new FailedException('数据不存在');
        }
        // 判断角色是否已分配用户
        $isEmpty = $role->getUsers()->isEmpty();
        if (!$isEmpty) {
            throw new FailedException('删除失败,该角色已分配用户');
        }      
        try {
            $this->transaction(function () use ($id, $role) {
                // 删除角色
                $this->model->deleteBy($id);
                // 删除部门关联
                $role->departments()->detach();
                // 删除菜单关联
                $role->menus()->detach();
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

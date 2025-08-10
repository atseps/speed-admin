<?php

namespace app\model\system\traits;

use app\model\system\Department;

trait RoleDepartmentTrait
{

    /**
     * 部门角色多对多关联
     * 
     * @return mixed
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'role_department', 'dept_id', 'role_id');
    }

    /**
     * 关联查询部门数据
     * 
     * @return mixed
     */
    public function getDepartments()
    {
        return $this->departments()->select();
    }



    /**
     * 关联查询部门id
     * 
     * @return mixed
     */
    public function getDepartmentId()
    {
        return $this->getDepartments()->column('id');
    }

    /**
     * 关联新增部门数据
     * 
     * @param array $departments
     * @return mixed
     */
    public function saveDepartments(array $departments)
    {
        if (empty($departments)) {
            return true;
        }

        sort($departments);

        return $this->departments()->attach($departments);
    }



    /**
     * 关联更新角色数据
     * 
     * @param array $departments
     * @return boolean
     */
    public function updateDepartments(array $departments)
    {

        $this->departments()->detach(); //删除关联数据
        $this->saveDepartments($departments); //新增关联数据
        return true;
    }
}

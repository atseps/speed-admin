<?php

namespace app\model\system;

use core\base\BaseModel;
use core\facade\Tree;

class Department extends BaseModel
{
    //排序
    public function setSortAttr($value)
    {
        return (int)$value;
    }

    /**
     * 获取子部门IDS
     *
     * @param $id
     * @return array
     */
    public static function getChildrenDepartmentIds($id)
    {
        $department = self::field(['id', 'parent_id'])->select()->toArray();
        $departmentIds = Tree::init($department, ['parentKey' => 'parent_id'])->getAllChildrenIds($id, true);
        return $departmentIds;
    }
}

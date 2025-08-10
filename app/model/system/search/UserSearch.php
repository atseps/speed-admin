<?php
namespace app\model\system\search;
use app\model\system\{Department,Role};
trait UserSearch
{
    //角色
    public function searchRolesAttr($query, $value){
        $user_id = Role::find($value)->getUsers()->column('id');
        $query->whereIn('id',$user_id);
    }
    //关键词
    public function searchKeyAttr($query, $value){
        $query->whereLike('username|realname|pinyin',trim($value));
    }
    //删除状态
    public function searchStatusAttr($query, $value){
        $query->where('status',$value);
    }
    //部门
    public function searchDeptidAttr($query, $value){

        $query->whereIn('dept_id', Department::getChildrenDepartmentIds($value));
    }

    //添加时间
    public function searchCreateTimeAttr($query, $value)
    {
        $query->whereTime('create_time', 'between', between_time($value));
    }
    
}

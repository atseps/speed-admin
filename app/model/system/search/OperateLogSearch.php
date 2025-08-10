<?php
namespace app\model\system\search;

trait OperateLogSearch
{
    //操作人
    public function searchUserIdAttr($query, $value){
        $query->where('user_id',$value);
    }
    //请求方式
    public function searchMethodAttr($query, $value){
        $query->where('method',$value);
    }
    //操作时间
    public function searchCreateTimeAttr($query, $value){
        $query->whereTime('create_time', 'between', between_time($value));
    }
}

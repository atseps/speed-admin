<?php

namespace app\model\system;

use core\base\BaseModel;

class GenerateTable extends BaseModel
{
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';


    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y/m/d H:i:s',
        'update_time'  =>  'timestamp:Y/m/d H:i:s'
    ];

    //表名称
    public function searchTableNameAttr($query, $value)
    {
        $query->where('table_name', $value);
    }

    //表描述
    public function searchTableCommentAttr($query, $value)
    {
        $query->where('table_comment', $value);
    }

    /**
     * 关联数据表字段
     * 
     */
    public function tableColumn()
    {
        return $this->hasMany(GenerateField::class, 'table_id', 'id');
    }
}

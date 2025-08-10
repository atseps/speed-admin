<?php

namespace app\model\system;

use core\base\BaseModel;

class GenerateField extends BaseModel
{
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y/m/d H:i:s',
        'update_time'  =>  'timestamp:Y/m/d H:i:s',
        'is_required' =>   'boolean',
        'is_insert' =>   'boolean' ,
        'is_list' =>   'boolean',
        'is_search' =>   'boolean'
    ];

    //查询方式获取器
    public function getSearchTypeAttr($value)
    {
        return htmlspecialchars_decode($value, ENT_QUOTES);
    }

}

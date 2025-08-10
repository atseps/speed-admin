<?php

namespace app\model\system;

use core\base\BaseModel;
use app\model\system\search\OperateLogSearch;
use app\model\system\User;

class OperateLog extends BaseModel
{

    use OperateLogSearch;

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y/m/d H:i:s'
    ];


    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->bind(['realname']);
    }
}

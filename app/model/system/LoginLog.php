<?php

namespace app\model\system;

use core\base\BaseModel;
use app\model\system\User;

class LoginLog extends BaseModel
{
    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'login_time';

    // 关闭自动写入update_time字段
    protected $updateTime = false;

    //定义类型转换
    protected $type = [
        'login_time'  =>  'timestamp:Y/m/d H:i:s'
    ];

    //账号
    public function searchAccountAttr($query, $value)
    {
        $query->where('account', $value);
    }


    //用户姓名
    public function searchRealnameAttr($query, $value)
    {
        $user_id = User::whereLike('realname',trim($value))->column('id');
        $query->whereIn('user_id', $user_id);
    }


    //操作时间
    public function searchLoginTimeAttr($query, $value)
    {
        $query->whereTime('login_time', 'between', between_time($value));
    }

    //定义用户相对关联
    public function user()
    {
        return $this->belongsTo(User::class, 'account', 'username')->bind(['realname']);
    }
}

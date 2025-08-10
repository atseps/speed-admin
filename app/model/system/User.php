<?php

namespace app\model\system;

use core\base\BaseModel;
use app\model\system\search\UserSearch;
use app\model\system\traits\UserRoleTrait;
use core\facade\Util;
class User extends BaseModel
{

    use UserSearch;
    use UserRoleTrait;

    //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

    // 关闭自动写入update_time字段
    protected $updateTime = false;
    //只读字段
    protected $readonly = ['username'];

    //定义类型转换
    protected $type = [
        'create_time'  =>  'timestamp:Y/m/d'
    ];


    //密码修改器
    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    //真实姓名修改器
    public function setRealnameAttr($value, $data)
    {
        $this->set('pinyin', Util::toPinyin($data['realname']));
        return $value;
    }

    //定义部门相对关联
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id')->bind(['department_name' => 'name']);
    }

    //名称搜索范围
    public function scopeSearchName($query, $name)
    {
        $query->whereLike('realname|pinyin', trim($name))->field('id,realname');
    }

}

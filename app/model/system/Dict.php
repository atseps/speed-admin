<?php

namespace app\model\system;

use core\base\BaseModel;
use app\service\system\DictService;
use core\exception\FailedException;

class Dict extends BaseModel
{

    const CATEGORYNAME = 'dict_type';

    //类型
    public function searchTypeAttr($query, $value)
    {
        $query->where('type', $value);
    }

    //名称
    public function searchKeyAttr($query, $value)
    {
        $query->whereLike('name', $value);
    }


    //模型事件更新前
    public static function onBeforeUpdate($model)
    {
        $map[] = ['id', "<>", $model->getAttr('id')];
        $map[] = ['value', "=", $model->getAttr('value')];
        $map[] = ['type', "=", $model->getAttr('type')];
        $validate = $model->where($map)->count('id');
        if ($validate > 0) {
            throw new FailedException('属性值已存在');
            return false;
        }
        return true;
    }


    //模型事件新增前
    public static function onBeforeInsert($model)
    {
        $map['type'] = $model->getAttr('type');
        $map['value'] = $model->getAttr('value');
        $validate = $model->where($map)->count('id');
        if ($validate > 0) {
            throw new FailedException('属性值已存在');
            return false;
        }
    }

    //模型事件写入后
    public static function onAfterWrite($model)
    {   
        $server = new DictService($model);
        $server->updateCache();
    }
}

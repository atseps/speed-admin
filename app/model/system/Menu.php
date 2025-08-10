<?php

namespace app\model\system;

use core\base\BaseModel;

class Menu extends BaseModel
{

    //菜单类型
    public function getTypeTextAttr($value, $data)
    {
        return get_dict_map('menu_type', $data['type'],'-',true);
    }

    //菜单状态
    public function getStatusAttr($value, $data)
    {
        $dict = [0 => '显示', 1 => '隐藏'];
        return $dict[$data['hidden']] ?? '';
    }

    //排序
    public function setSortAttr($value)
    {
        return (int)$value;
    }

}

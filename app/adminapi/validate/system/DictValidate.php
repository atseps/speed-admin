<?php

namespace app\adminapi\validate\system;

use core\base\BaseValidate;

class DictValidate extends BaseValidate
{
    protected $rule = [
      'type|字典类型'  =>  'require|max:50',
      'name|字典名称' =>  'require|max:30',
      'value|字典值'   =>  'require|max:30',
      'sort|排序'=> 'number|between:1,9999',
      'note|备注'=>'max:255',
      'color|颜色'=>'max:10',
      'widget_type|组件类型'=>'in:tag,badge,text',
    ];

    protected $message = [
      'type.require' => '字典类型必填',
      'name.require' => '字典名称必填',
      'value.require' => '字典值必填',
      'sort.between' => '排序只能在1~9999之间'
    ];
}

<?php

namespace app\adminapi\validate\system;

use core\base\BaseValidate;
use app\model\system\Menu;
class MenuValidate extends BaseValidate
{
    protected $rule = [
        'pid'  =>  'require|checkParentId',
        'title' =>  'require|max:30',
        'path' =>  'require|checkRoutePath|max:255',
        'component' =>  'require|max:255',
        'sort' => 'number|between:1,9999',
        'open_type'=>'in:0,1,2',
        'type'=>'in:0,1,2',
        'icon'=>'max:100',
        'rules'=>'max:100',
        'active_key'=>'max:255',
        'link_url'=>'url|max:500'
    ];

    protected $message = [
        'pid.require' => '父级菜单必选',
        'title.require' => '菜单名称必填',
        'title.max' => '菜单名称不能超过30个字符',
        'path.require' => '路由地址必填',
        'path.checkRoutePath'=> '同级菜单路由地址不能重复',
        'path.max' => '地址不能超过255个字符',
        'path.url' => '外链地址不是一个有效的URL地址',
        'link_url.require'=> '内链地址不能为空',
        'link_url.url'=> '内链地址不是一个有效的URL地址',
        'link_url.max' => '内链地址不能超过500个字符',
        'component.require' => '路由组件必填',
        'component.max' => '路由组件不能超过255个字符',
        'type.in' => '菜单类型参数值错误',
        'open_type.in' => '打开方式参数值错误',
        'rules|max' => '权限节点不能超过100个字符',
        'active_key.max' => '高亮导航不能超过255个字符',
        'sort.between' => '排序只能在1~9999之间'
    ];

    // 权限节点验证场景定义
    public function sceneRules()
    {
        return $this->only(['title', 'pid', 'sort','rules'])->append('rules', 'require');
    }

    // 內链验证场景定义
    public function sceneLinkUrl()
    {
        return $this->only(['title', 'pid', 'path','sort','link_url'])->append('link_url', 'require');
    }

    // 外链验证场景定义
    public function sceneExternalLink()
    {
        return $this->only(['title', 'pid', 'path','sort'])->append('path', 'url');
    }


     /**
     * 同级菜单路由地址不能重复
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkRoutePath($value, $rule, $data)
    {
        $map[] = ['path', '=', $value];
        $map[] = ['pid', '=', $data['pid']];
        if(isset($data['id']) && !empty($data['id'])){
            $map[] = ['id', '<>', $data['id']];
        }   
        $count = Menu::where($map)->count();
        if ($count > 0) {
            return false;
        }
        return true;
    }

     /**
     * 校验上级菜单
     * @param $value
     * @param $rule
     * @param $data
     * @return bool|string
     */
    protected function checkParentId($value, $rule, $data)
    {
        if (!empty($data['id']) && $data['id'] == $value) {
            return '上级菜单不能选择自己';
        }
        return true;
    }

}

<?php

namespace app\adminapi\middleware;

use app\Request;
use app\model\system\Menu;
use core\facade\Util;

class RecordOperate
{

    //模块
    protected $module = ['login'];

    //控制器
    protected $controller = [];

    //权限节点
    protected $rule = [
        'system:dict:get',
        'system:operatelog:index'
    ];


    /**
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        $rules = get_rule();
        // 模块忽略
        $result = $this->exclude($rules);
        // 获取节点
        $permission = $this->getPermission($rules);
        // 触发事件
        $result && $this->operateEvent($permission);
        return $next($request);
    }


    /**
     * 忽略规则
     * @param $rules
     * @return bool
     */
    public function exclude($rules)
    {
        [$module, $controller] = Util::parseRule();
        //忽略模块
        if (in_array($module, $this->module)) {
            return false;
        }
        //忽略控制器
        if (in_array($controller, $this->controller)) {
            return false;
        }
        //忽略方法
        if (in_array($rules, $this->rule)) {
            return false;
        }
        return true;
    }



    /**
     * 获取权限节点
     * 
     * @param string $rules
     * @return mixed
     */
    protected function getPermission($rules)
    {
        return Menu::where('rules', $rules)->cache(60)->find();
    }

    /**
     * 操作日志
     *
     * @param $permission
     * @return void
     */
    protected function operateEvent($permission)
    {
        // 操作日志
        $permission && event('OperateLog', $permission);
    }
}

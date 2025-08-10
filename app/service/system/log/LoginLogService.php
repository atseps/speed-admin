<?php

namespace app\service\system\log;

use core\base\BaseService;
use app\model\system\LoginLog;
use think\facade\Db;

class LoginLogService extends BaseService
{


    public function __construct(LoginLog $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        return $this->model->search()->order('id', 'desc')->with(['user'])->paginate();
    }


    /**
     * 删除
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->deleteBy($id);
    }


    /**
     * 清空登录日志
     *
     * @return int
     */
    public function clear()
    {
        $tableName = $this->model->getTable(); // 获取当前模型的表名
        return Db::execute("TRUNCATE TABLE {$tableName}");
    }



    /**
     * 导出日志
     *
     */
    public function export()
    {
        $column = [
            'account' => '登录账号',
            'realname'  =>  '姓名',          
            'login_ip' =>  '登录IP',
            'login_time' => '登录时间',
            'browser' => '浏览器',
            'os' => '操作系统',
        ];
        $this->model->search()->order('id', 'desc')->with(['user'])->select()->export('登录日志',$column);
    }
}

<?php

namespace app\service\system\log;

use core\base\BaseService;
use app\model\system\OperateLog;
use think\facade\Db;

class OperateLogService extends BaseService
{

    
    public function __construct(OperateLog $model)
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
     */
    public function clear()
    {
        $tableName = $this->model->getTable(); // 获取当前模型的表名
        return Db::execute("TRUNCATE TABLE {$tableName}");
    }
}

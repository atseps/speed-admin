<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\system\log\OperateLogService;

class OperateLog extends BaseController
{


    private $service;

    function __construct(OperateLogService $service)
    {
        parent::__construct();
        $this->service = $service;
    }


    /**
     * 列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $this->success($this->service->getList());
    }


    /**
     * 删除
     *
     * @return \think\Response
     */
    public function delete()
    {
        $id = $this->request->param('id');
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }


    /**
     * 清空登录日志
     *
     * @return \think\Response
     */
    public function clear()
    {
        $this->service->clear();
        $this->success('清空成功');
    }
}

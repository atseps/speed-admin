<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\system\permission\AuthAccessService;

class AuthAccess extends BaseController
{

    private $service;

    function __construct(AuthAccessService $service)
    {
        parent::__construct();
        $this->service = $service;
    }



    /**
     * 列表
     * 
     * @return  \think\Response
     */
    public function index()
    {
        $roleid = $this->request->param('id');
        $data = $this->service->getList($roleid);
        $this->success($data);
    }


    /**
     * 新增
     * 
     * @return  \think\Response
     */
    public function save()
    {
        $role_id = $this->request->param('role_id');
        $menu_id = $this->request->param('menu_id');
        $result = $this->service->save($role_id, $menu_id);
        $this->success('权限设置成功');
    }
}

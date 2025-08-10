<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\system\permission\MenuService;

class Menu extends BaseController
{

    private $service;

    function __construct(MenuService $service)
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
        $data = $this->service->getList();
        $this->success($data);
    }


    /**
     * 获取路由
     * 
     * @return  \think\Response
     */
    public function getRouter()
    {
        $menus = $this->service->getRouter();
        $this->success($menus);
    }


    /**
     * 新增
     *
     * @return \think\Response
     */
    public function save()
    {
        $data = $this->request->param();
        $result = $this->service->save($data);
        $result ? $this->success('添加成功') : $this->error('添加失败');
    }




    /**
     * 更新
     *
     * @param  int  $id
     * @return \think\Request
     */
    public function update($id)
    {
        $data = $this->request->param();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }


    /**
     * 删除
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}

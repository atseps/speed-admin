<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\adminapi\validate\system\RoleValidate;
use app\service\system\permission\RoleService;

class Role extends BaseController
{

    private $service;


    function __construct(RoleService $service)
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
     * 所有角色
     *
     * @return \think\Response
     */
    public function all()
    {
        $data = $this->service->getAll();
        $this->success($data);
    }




    /**
     * 获取编辑的数据
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->service->edit($id);
        $this->success($data);
    }



    /**
     * 新增
     *
     * @return \think\Response
     */
    public function save(RoleValidate $roleValidate)
    {
        $data = $roleValidate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('角色新增成功') : $this->error('角色新增失败');
    }



    /**
     * 更新
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id,RoleValidate $roleValidate)
    {
        $data = $roleValidate->validated();
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

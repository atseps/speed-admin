<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\system\DepartmentService;
use app\adminapi\validate\system\DepartmentValidate;

class Department extends BaseController
{

    private $service;

    function __construct(DepartmentService $service)
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
     * 新增
     *
     * @param  \think\Request
     * @return \think\Response
     */
    public function save()
    {
        $data = $this->request->param(['name', 'parent_id', 'sort', 'leader_id' => '']);
        validate(DepartmentValidate::class)->check($data);
        $result = $this->service->save($data);
        $result ? $this->success('添加成功') : $this->error('添加失败');
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
     * 更新
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id)
    {
        $data = $this->request->param(['id', 'name', 'parent_id', 'sort',  'leader_id' => '']);
        validate(DepartmentValidate::class)->check($data);
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }


    /**
     * 删除
     *
     * @param  int  $id
     * @return mixed
     */
    public function delete($id)
    {
        $result = $this->service->delete($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }
}

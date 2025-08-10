<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\system\DictService;
use app\adminapi\validate\system\DictValidate;

class Dict extends BaseController
{
    
    private $service;

    function __construct(DictService $service)
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
     * 获取字典缓存
     * 
     * @return  \think\Response
     */
    public function get()
    {
        $type = $this->request->param('type');
        $str = $this->request->param('str/b', false);
        $data = $this->service->getDictData($type, $str);
        $data ? $this->success($data, '字典获取成功') : $this->error('字典获取失败');
    }


    /**
     * 新增
     * 
     * @return \think\Response
     */
    public function save(DictValidate $DictValidate)
    {
        $data = $DictValidate->validated();
        $result = $this->service->save($data);
        $result ? $this->success('添加成功') : $this->error('添加失败');
    }



    /**
     * 更新
     * 
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id,DictValidate $DictValidate)
    {
        $data = $DictValidate->validated();
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 更新排序
     * 
     * @return \think\Response
     */
    public function updateSort()
    {
        $data = $this->request->param();
        $result = $this->service->updateSort($data);
        $this->success('更新成功');
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


 
    /**
     * 
     * 修改状态
     * @param  int  $id
     * @return \think\Response
     */
    public function changeStatus($id)
    {
        $result = $this->service->changeStatus($id);
        $result ? $this->success('修改成功') : $this->error('修改失败');
    }
    

    /**
     * 更新字典缓存
     * 
     * @return  \think\Response
     */
    public function updateCache()
    {
        $result = $this->service->updateCache();
        $result ? $this->success('缓存更新成功') : $this->error('缓存更新失败');
    }
}

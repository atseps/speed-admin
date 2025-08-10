<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\system\GeneratorService;
use app\adminapi\validate\system\GeneratorValidate;
use think\facade\Env;

class Generator extends BaseController
{

    private $service;


    function __construct(GeneratorService $service)
    {
        parent::__construct();
        $this->service = $service;
        if (Env::get('app_debug') == false) {
            $this->error('代码生成仅开发模式下可用');
        }
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
     * 获取所有的数据表
     *
     * @return \think\Response
     */
    public function getAllTable()
    {
        $params = $this->request->param(['table_name', 'table_comment']);
        $data = $this->service->getDatabaseTable($params);
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
    public function save(GeneratorValidate $generatorValidate)
    {
        $data = $generatorValidate->validated('add');
        $result = $this->service->save($data['table']);
        $result ? $this->success(['id' => $result], '导入成功') : $this->error('导入失败');
    }



    /**
     * 更新
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function update($id, GeneratorValidate $generatorValidate)
    {
        $data = $generatorValidate->validated('edit');
        $result = $this->service->update($id, $data);
        $result ? $this->success('保存成功') : $this->error('保存失败');
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
     * 删除字段
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function deleteFiled($id)
    {
        $result = $this->service->deleteFiled($id);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }


    /**
     * 生成代码
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function makeCode($id)
    {
        $result = $this->service->makeCode($id);
        $result ? $this->success($result, '生成成功') : $this->error('生成失败');
    }

    /**
     * 预览代码
     * @param  int  $id
     * @return \think\Response
     */
    public function preview($id)
    {
        $result = $this->service->preview($id);
        $result ? $this->success($result) : $this->error($this->service->getError());
    }


    /**
     * 下载文件
     * 
     */
    public function download()
    {
        $file = $this->request->param('file');
        if (empty($file)) {
            $this->error('文件不能为空');
        }
        $result = $this->service->download($file);
        if ($result) {
            return download($result, 'speedadmin-curd.zip');
        }
        return $this->error('下载失败');
    }
}

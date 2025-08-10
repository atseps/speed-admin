<?php

namespace app\service\system;

use core\base\BaseService;
use app\model\system\{Department, User};
use core\exception\FailedException;

class DepartmentService extends BaseService
{

    public function __construct(Department $model)
    {
        $this->model = $model;
    }



    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $data = $this->model->search()->field(['*', 'id' => 'value', 'name' => 'title'])->select()->toTree(0,'parent_id');
        return $data;
    }


    /**
     * 保存
     * @param array $data
     * @return int|bool
     */
    public function save(array $data)
    {
        return $this->model->storeBy($data);
    }


    /**
     * 修改
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, array $data)
    {
        return $this->model->updateBy($id, $data);
    }


    /**
     * 获取编辑的数据
     * @param int $id
     * @return array
     */
    public function edit(int $id)
    {
        $data =  $this->model->findOrFail($id);
        $data->leader_user = User::where('id', $data->leader_id)->field('id,realname')->find();
        return $data;
    }



    /**
     * 删除
     * @param  string $id  
     * @return bool
     */
    public function delete($id)
    {
        $data = $this->model->find($id);
        if (!$data) throw new FailedException('数据不存在');
        //是否存在子部门
        $children = $this->model->where('parent_id', $id)->find();
        if ($children) throw new FailedException('存在子部门,无法删除!');
        //是否存在用户
        $user = User::where('dept_id', $id)->find();
        if (!is_null($user)) throw new FailedException('该部门下存在用户,无法删除!');
        $result = $this->model->deleteBy($id);
        return $result;
    }
}

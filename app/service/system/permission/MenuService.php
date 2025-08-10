<?php

namespace app\service\system\permission;

use core\base\BaseService;
use app\model\system\{Menu, User, AuthAccess};
use core\exception\FailedException;
use app\adminapi\validate\system\MenuValidate;

class MenuService extends BaseService
{

    public function __construct(Menu $model)
    {
        $this->model = $model;
    }


    /**
     * 列表
     *
     * @return array
     */
    public function getList()
    {
        $data = $this->model->field('*,id as value')->append(['type_text', 'status'])->sort('asc')->select()->toTree();
        return $data;
    }



    /**
     * 添加
     * @param  array $data
     * @return  int
     */
    public function save($data)
    {
        $this->validate($data);
        $menuid = $this->model->storeBy($data);
        //超级管理员自动写入菜单
        $AuthAccess = app()->make(AuthAccessService::class);
        $AuthAccess->create($menuid, config('system.super_admin_id'));
        return $menuid;
    }



    /**
     * 更新
     * @param  string $id
     * @param  array $data
     * @return  bool
     */
    public function update($id, $data)
    {
        $this->validate($data);
        $result = $this->model->updateBy($id, $data);
        return $result;
    }



    /**
     * 删除
     * @param  string $id
     * @return  bool
     */
    public function delete($id)
    {
        $children = $this->model->where('pid', $id)->find();
        if ($children) {
            throw new FailedException('删除失败,存在子菜单无法删除！');
        }
        try {
            $this->transaction(function () use ($id) {            
                //删除菜单数据
                $this->model->deleteBy($id);
                //删除权限关联表数据
                $AuthAccess = app()->make(AuthAccessService::class);
                $AuthAccess->delete($id);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 获取路由
     * @return  array
     */
    public function getRouter()
    {
        $roleid = User::find(request()->uid())->getRolesId();
        $menuid = AuthAccess::getPermission($roleid);
        $router = $this->model->whereIn('id', $menuid)->order('sort', 'asc')->whereIn('type', [0, 1])->select()->toTree();
        return $router;
    }


    /**
     * 获取全部菜单节点数据
     * @return  array 
     */
    public static function getRuleAll()
    {   
        $map[] = ['type','<>',3];
        $node = Menu::field('id,title,pid')->where($map)->order('sort', 'asc')->select()->toTree();
        return $node;
    }


    /**
     * 验证
     * @throws \think\ValidateException
     */
    public function validate($data)
    {
        if (!isset($data['type'])) {
            throw new FailedException('菜单类型不能为空');
        }
        if ($data['type'] == 2) {
            //验证权限节点
            validate(MenuValidate::class)->scene('rules')->check($data);
        } else {
            if (!isset($data['open_type'])){
                throw new FailedException('打开方式不能为空');
            }   
            if($data['open_type'] == 1){
                validate(MenuValidate::class)->scene('linkUrl')->check($data);
            }else if($data['open_type'] == 2){
                validate(MenuValidate::class)->scene('externalLink')->check($data);
            }else{
                validate(MenuValidate::class)->check($data);
            }
        }
    }
}

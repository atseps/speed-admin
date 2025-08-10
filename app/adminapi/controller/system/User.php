<?php

namespace app\adminapi\controller\system;

use core\base\BaseController;
use app\service\user\UserService;
use app\adminapi\validate\system\{UserValidate, PasswordValidate};

class User extends BaseController
{

    private $service;

    function __construct(UserService $service)
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
     * 获取激活的用户列表
     *
     * @return \think\Response
     */
    public function getActiveUsers()
    {
        $data = $this->service->getActiveUsers();
        $this->success($data);
    }

    /**
     * 新增
     *
     * @return \think\Response
     */
    public function save()
    {
        $data = $this->request->param(['username','phone','email','roles','realname','dept_id','avatar']);
        validate(UserValidate::class)->scene('add')->check($data);
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
        $data = $this->request->param(['id','email', 'roles', 'realname', 'phone', 'dept_id','avatar']);
        validate(UserValidate::class)->scene('edit')->check($data);
        $result = $this->service->update($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }




    /**
     * 更新个人信息
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function updateInfo()
    {
        $id = request()->uid();
        $data = $this->request->param(['email', 'realname', 'phone','avatar']);
        $data['id'] = $id;
        validate(UserValidate::class)->scene('updateInfo')->check($data);
        $result = $this->service->updateInfo($id, $data);
        $result ? $this->success('更新成功') : $this->error('更新失败');
    }

    /**
     * 根据id获取用户
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function getUserById()
    {
        $ids = $this->request->param('id');
        if (!$ids) {
            $this->error('参数错误');
        }
        $ids = explode(",", $ids);
        $data = $this->service->getUserById($ids);
        $this->success($data);
    }

    

    /**
     * 修改密码
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function changePassword()
    {
        $data = $this->request->param();
        $data['id'] = request()->uid();
        validate(PasswordValidate::class)->check($data);
        $result = $this->service->changePassword($data);
        $result ? $this->success('修改成功') : $this->error('修改失败');
    }




    /**
     * 重置密码
     * 
     * @param  int  $id
     * @return \think\Response
     */
    public function resetPassword($id)
    {
        validate(UserValidate::class)->scene('checkUser')->check(['id'=>$id]);
        $result = $this->service->resetPassword($id);
        $result ? $this->success($result, '重置成功') : $this->error('重置失败');
    }




    /**
     * 修改状态
     * 
     * @param  int  $id
     * @return \think\Response
     */
    public function changeStatus($id)
    {
        validate(UserValidate::class)->scene('checkUser')->check(['id'=>$id]);
        $result = $this->service->changeStatus($id);
        $result ? $this->success('修改成功') : $this->error('修改失败');
    }





    /**
     * 获取用户信息
     * 
     * @return \think\Response
     */
    public function getUserInfo()
    {
        $data = $this->service->getUserInfo();
        $this->success($data);
    }
}

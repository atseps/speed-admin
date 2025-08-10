<?php

namespace app\adminapi\controller\login;

use core\base\BaseController;
use app\service\user\AuthService;
use core\exception\FailedException;
class Index extends BaseController
{


    /**
     * 登录
     * @return \think\Response
     */
    public function login(AuthService $auth)
    {
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        if (!$username || !$password) {
            throw new FailedException('用户名或密码不能为空');
        }
        $token = $auth->login($username, $password);
        $this->success($token, '登录成功');
    }

    /**
     * 退出登录
     * @return \think\Response
     */
    public function logout(AuthService $auth)
    {
        $refresToken = $this->request->post('refreshToken');
        if($refresToken) {
            $auth->logout($refresToken);
            $this->success('退出登录成功');
        }
        $this->error('退出登录失败');
    }

    
    /**
     * 刷新令牌
     * @return \think\Response
     */
    public function refreshToken(AuthService $auth)
    {
        try {
            $token = $auth->refreshToken();
        } catch (\Exception $e) {
           throw new FailedException($e->getMessage(), httpCode:401);
        }
        $this->success($token);
    }


}

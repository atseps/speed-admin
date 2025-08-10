<?php

namespace app\service\user;

use app\model\system\User;
use app\service\user\LoginLimitService;
use core\base\BaseService;
use core\service\jwt\Factory;
use core\exception\FailedException;

class AuthService extends BaseService
{
    // 保存用户信息
    protected $user = null;
    protected $jwtAuth;

    public function __construct()
    {
        parent::__construct();
        $this->jwtAuth = Factory::getInstance();
    }

    /**
     * 后台登录
     *
     * @param string $username
     * @param string $password
     * @return string
     * @throws ValidateException
     */
    public function login(string $username, string $password): array
    {
        $limiter = new LoginLimitService();

        if ($limiter->checkLockout($username)) {
            throw new FailedException('由于多次输入错误密码，请10分钟后重试');
        }

        // 获取用户信息
        $user = User::field('status,id,password')->getByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            $limiter->recordFailedAttempt($username);
            throw new FailedException('用户名或密码不正确');
        }

        // 如果密码正确，清除登录尝试记录
        $limiter->clearAttempts($username);

        if ($user->status == User::DISABLE) {
            throw new FailedException('你的账号已禁用，请与管理员联系');
        }

        $roleId = User::find($user->id)->getRolesId();

        if ($roleId === null) {
            throw new FailedException('你的账号尚未设置角色，请与管理员联系');
        }

        User::update([
            'last_login_time' => time(),
            'last_login_ip' => request()->ip()
        ], ['id' => $user->id]);

        event('LoginLog', [$username, $user->id]);

        return $this->jwtAuth->generateToken(['id' => $user->id]);
    }

    /**
     * 获取用户
     *
     * @return User|null
     */
    public function user(): ?User
    {
        if (is_null($this->user)) {
            $this->user = User::where('id', request()->uid())->field('id,dept_id')->find();
        }
        return $this->user;
    }



    /**
     * 退出登录
     * @param string $refreshToken 刷新令牌
     */
    public function logout(string $refreshToken): void
    {
        $refreshToken = str_replace('Bearer ', '', $refreshToken);
        $accessToken = $this->jwtAuth->getToken();
        try {
            $this->jwtAuth->verifyAccessToken();
            $this->jwtAuth->addBlacklist($accessToken);
        } catch (\Exception) {}
        
        try {
            $this->jwtAuth->verifyRefreshToken($refreshToken);
            $this->jwtAuth->addBlacklist($refreshToken);
        } catch (\Exception) {}
    }


    /**
     * 刷新令牌
     *
     * @return string
     */
    public function refreshToken(): array
    {
        $token = $this->jwtAuth->getToken();
        return $this->jwtAuth->refreshToken($token);
    }
}

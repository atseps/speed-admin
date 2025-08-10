<?php

namespace app\service\user;

use think\facade\Cache;
use think\facade\Request;

class LoginLimitService
{
    protected $cachePrefix = 'login_attempts_';
    protected $maxAttempts = 10;
    protected $lockoutTime = 600; // 单位为秒，即10分钟

    public function __construct($maxAttempts = 10, $lockoutTime = 600)
    {
        $this->maxAttempts = $maxAttempts;
        $this->lockoutTime = $lockoutTime;
    }

    /**
     * 检查是否需要锁定
     *
     * @param string $username 用户名
     * @return bool
     */
    public function checkLockout($username)
    {
        $ip = Request::ip();
        $cacheKey = $this->getCacheKey($ip, $username);
        $attemptsInfo = Cache::get($cacheKey);

        if (!$attemptsInfo) {
            return false;
        }

        if ($attemptsInfo['count'] >= $this->maxAttempts && (time() - $attemptsInfo['last_attempt_time']) < $this->lockoutTime) {
            return true;
        }

        return false;
    }

    /**
     * 记录一次失败的登录尝试
     *
     * @param string $username 用户名
     */
    public function recordFailedAttempt($username)
    {
        $ip = Request::ip();
        $cacheKey = $this->getCacheKey($ip, $username);
        $attemptsInfo = Cache::get($cacheKey) ?: ['count' => 0, 'last_attempt_time' => time()];

        $attemptsInfo['count'] += 1;
        $attemptsInfo['last_attempt_time'] = time();

        Cache::set($cacheKey, $attemptsInfo, $this->lockoutTime);
    }

    /**
     * 清除登录尝试记录
     *
     * @param string $username 用户名
     */
    public function clearAttempts($username)
    {
        $ip = Request::ip();
        $cacheKey = $this->getCacheKey($ip, $username);
        Cache::delete($cacheKey);
    }

    /**
     * 获取缓存键名
     *
     * @param string $ip
     * @param string $username
     * @return string
     */
    protected function getCacheKey($ip, $username)
    {
        return $this->cachePrefix . md5($ip . '_' . $username);
    }
}

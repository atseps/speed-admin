<?php

namespace app\adminapi\event;

use app\model\system\LoginLog;

class LoginLogEvent
{
    public function handle($user, LoginLog $login_log)
    {
        $agent = request()->header('user-agent');
        list($username, $id) = $user;
        $login_log->storeBy([
            'account' => $username,
            'login_ip'   => get_client_ip(),
            'browser'    => $this->getBrowser($agent),
            'os'         => $this->getOs($agent),
            'user_id'    => $id
        ]);
    }


    /**
     * 获取操作系统
     * 
     * @param $agent
     * @return string
     */
    private function getOs($agent): string
    {
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.1/i', $agent)) {
            return 'Windows 7';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.2/i', $agent)) {
            return 'Windows 8';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 10.0/i', $agent)) {
            return 'Windows 10';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 5.1/i', $agent)) {
            return 'Windows XP';
        }
        if (false !== stripos($agent, 'linux')) {
            return 'Linux';
        }
        if (false !== stripos($agent, 'mac')) {
            return 'Mac';
        }

        return '未知';
    }

    /**
     * 获取浏览器
     * 
     * @param $agent
     * @return string
     */
    private function getBrowser($agent): string
    {
        if (false !== stripos($agent, "MSIE")) {
            return 'MSIE';
        }
        if (false !== stripos($agent, "Firefox")) {
            return 'Firefox';
        }
        if (false !== stripos($agent, "Chrome")) {
            return 'Chrome';
        }
        if (false !== stripos($agent, "Safari")) {
            return 'Safari';
        }
        if (false !== stripos($agent, "Opera")) {
            return 'Opera';
        }

        return '未知';
    }
}

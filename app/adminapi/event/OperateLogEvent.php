<?php

namespace app\adminapi\event;

use app\model\system\{OperateLog, Menu};

class OperateLogEvent
{
    public function handle($permission)
    {

        $parentPermission = Menu::where('id', $permission->pid)->value('title');

        $requestParams = request()->param();

        // 如果参数过长则不记录
        if (!empty($requestParams)) {
            if (strlen(\json_encode($requestParams)) > 1000) {
                $requestParams = [];
            }
        }

        OperateLog::create([
            'user_id' => request()->uid(),
            'module'     => $parentPermission ?: '',
            'method'     => request()->method(),
            'operate'    => $permission->title,
            'route'      => $permission->rules,
            'params'     => !empty($requestParams) ? json_encode($requestParams, JSON_UNESCAPED_UNICODE) : '',
            'create_time' => time(),
            'ip'         => get_client_ip()
        ]);
    }
}

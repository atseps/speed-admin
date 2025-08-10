<?php
// 事件定义文件
return [
    'bind'      => [],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'LoginLog' => ['app\adminapi\event\LoginLogEvent'], //登录日志事件
        'OperateLog' => ['app\adminapi\event\OperateLogEvent'], //操作日志事件
    ],

    'subscribe' => [],
];

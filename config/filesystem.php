<?php

use think\facade\Env;

return [
    // 默认磁盘 
    'default' => Env::get('filesystem.driver', 'local'),
    //本地上传的目录
    'folder'  => 'a',
    // 磁盘列表
    'disks'   => [
        'local'  => [
            'type' => 'local',
            'root'       => app()->getRootPath() . 'public/storage',
            'url'        => '/storage',
            'visibility' => 'public',
            'domain' => Env::get('upload.url',  get_root_host() . '/storage' . DIRECTORY_SEPARATOR)
        ],
        'oss' => [
            'type'         => 'aliyun',
            'accessId'     => '******',
            'accessSecret' => '******',
            'bucket'       => '',
            'endpoint'     => '',
            'url'          => ''
        ],
        'qiniu'  => [
            'type'      => 'qiniu',
            'accessKey' => '******',
            'secretKey' => '******',
            'bucket'    => 'bucket',
            'url'       => '', //不要斜杠结尾，此处为URL地址域名。
        ],
        'qcloud' => [
            'type'       => 'qcloud',
            'region'      => '***', //bucket 所属区域 英文
            'appId'      => '***', // 域名中数字部分
            'secretId'   => '***',
            'secretKey'  => '***',
            'bucket'          => '***',
            'timeout'         => 60,
            'connect_timeout' => 60,
            'cdn'             => '您的 CDN 域名',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
        ]
    ]
];

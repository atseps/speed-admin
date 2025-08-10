<?php
// +----------------------------------------------------------------------
// | 系统配置
// +----------------------------------------------------------------------

return [
    //上传配置
    'upload' => [
        'image' => [
            'fileSize' => 10485760, // 10MB
            'fileExt' => ['jpg', 'png', 'gif', 'jpeg', 'webp'],
        ],
        'attachment'  => [
            'fileSize' => 10485760, // 10MB
            'fileExt' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'],
        ],
        "file" => [
            'fileSize' => 10485760, // 10MB
            'fileExt' => ['jpg', 'png', 'gif', 'jpeg', 'webp', 'mp4', 'mp3', 'amr', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'],
        ],
    ],
    //高德地图key
    'map_key' => '',
    //加密函数密钥
    'secret_key' => '*******',
    //超级管理员id
    "super_admin_id" => 1,
    //用户初始密码
    'def_password' => '123456',
    //列表每页最大的数量
    'page_size_max' => 500,
    //默认头像
    'default_avatar' => 'https://gw.alipayobjects.com/zos/rmsportal/BiazfanxmamNRoxxVxka.png',
];

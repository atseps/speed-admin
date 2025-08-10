<?php

use Lcobucci\JWT\Signer\Hmac\Sha256;

return [
    'default' => [
        // 自定义数据中必须存在uid的键值，这个key你可以自行定义，只要自定义数据中存在该键即可
        'key'                => 'id',
        // 密钥
        'secret'                 => env('JWT_SECRET', '6K6GeSAHHZyX3TXsrkZPAxe3Gkw0HJCH'),
        // 过期时间
        'ttl'                    => env('JWT_TTL', 3600),
        // 刷新令牌过期时间
        'refresh_ttl'            => env('JWT_REFRESH_TTL', 7200),
        // jwt使用到的缓存前缀
        'cache_prefix'           => 'speed_jwt',
        // 黑名单缓存过期时间
        'blacklist_ttl' => 7200,
        //黑名单宽限期
        'blacklist_grace_period' => 10,
        // 签名算法 
        'alg' => new Sha256(),
    ],
    // 其他场景的令牌配置，比如api应用的令牌可以定义不同的密钥、过期时间满足多种类型的应用接口认证
    'api' => [
        'secret'     => '4FBJxAjrj5wA7EsAk2e4TRp3FP971AdQ',
        'ttl'        => 3600,
        'refresh_ttl' => 7200,
    ],
];

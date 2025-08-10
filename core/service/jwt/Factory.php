<?php

namespace core\service\jwt;

use core\service\jwt\JwtAuth;
use think\facade\Config;
use think\helper\Arr;

class Factory
{
    public static function getInstance(string $name = 'default')
    {
        return app(JwtAuth::class, [
            'config' => self::getConfig($name),
            'scene'  => $name
        ]);
    }


    public static function getConfig(string $scene): array
    {
        if ($scene === 'default') {
            return Config::get(self::getConfigKey());
        }
        return Arr::mergeDeep(
            Config::get(self::getConfigKey()),
            Config::get(self::getConfigKey($scene))
        );
    }

    private static function getConfigKey(string $name = 'default'): string
    {
        return 'jwt.' . $name;
    }
}

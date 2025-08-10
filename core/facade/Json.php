<?php

namespace core\facade;

use think\Facade;

class Json extends Facade
{
    protected static function getFacadeClass()
    {
        return 'core\service\JsonServer';
    }
}

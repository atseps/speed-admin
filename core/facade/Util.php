<?php

namespace core\facade;

use think\Facade;

class Util extends Facade
{
    protected static function getFacadeClass()
    {
        return 'core\utils\Util';
    }
}

<?php

namespace core\facade;

use think\Facade;

class Excel extends Facade
{
    protected static function getFacadeClass()
    {
        return 'core\service\excel\Excel';
    }
}

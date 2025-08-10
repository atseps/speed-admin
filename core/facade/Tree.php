<?php

namespace core\facade;

use think\Facade;

class Tree extends Facade
{
    protected static function getFacadeClass()
    {
        return 'core\utils\Tree';
    }
}

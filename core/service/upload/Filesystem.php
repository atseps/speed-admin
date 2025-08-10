<?php

namespace core\service\upload;

use think\Facade;

class Filesystem extends Facade
{
    protected static function getFacadeClass()
    {
        return 'thans\filesystem\Filesystem';
    }
}

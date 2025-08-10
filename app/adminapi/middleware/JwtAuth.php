<?php

namespace app\adminapi\middleware;

use core\service\jwt\Factory;
use core\exception\FailedException;
use Exception;
class JwtAuth
{
    public function handle($request, \Closure $next)
    {
        $jwt = Factory::getInstance();
        try {
            $userInfo = $jwt->verifyAccessToken();
            $request->macro('uid', fn() => $userInfo['id']);
        } catch (Exception $e) {
            throw new FailedException($e->getMessage(), 401);
        }
        return $next($request);
    }
}

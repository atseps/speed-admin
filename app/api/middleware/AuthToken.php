<?php
namespace app\api\middleware;
use core\service\jwt\Factory;
use core\exception\FailedException;
use Exception;

class AuthToken {

	public function handle($request, \Closure $next)
    {
        $jwt = Factory::getInstance('api');
        try {
            $userInfo = $jwt->verifyAccessToken();
            $request->macro('user_id', fn() => $userInfo['id']);
        } catch (\Exception $e) {
            throw new FailedException($e->getMessage(), 401);
        }
        return $next($request);
    }

}

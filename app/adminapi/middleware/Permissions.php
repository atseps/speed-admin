<?php

namespace app\adminapi\middleware;
use core\exception\FailedException;

class Permissions
{

	public function handle($request, \Closure $next)
	{
		$result = auth_check();
		if (!$result) {
			throw new FailedException('你无权操作此项，请与管理员联系！', httpCode:403);
		}
		return $next($request);
	}
}

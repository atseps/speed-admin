<?php

declare(strict_types=1);

namespace core\middleware;

class DecryptionId
{

    public function handle($request, \Closure $next,$field = 'id')
    {
        $value = $request->param($field);
        if($value){
            $id = auth_code($value, false);
            $request->setRoute([$field => $id]);
        }
        return $next($request);
    }
}

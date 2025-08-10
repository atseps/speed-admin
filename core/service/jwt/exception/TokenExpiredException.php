<?php


namespace core\service\jwt\exception;
use Exception;
/**
 * 令牌过期的异常
 *
 */
class TokenExpiredException extends Exception
{
    protected $message = '您没有登录或登录已过期，请重新登录';
}

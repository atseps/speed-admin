<?php

namespace core\exception;

use think\Exception;

class BaseException extends Exception
{
    // 默认错误码
    protected $code = 0;
    
    // 错误消息
    protected $message = '';
    
    // 自定义数据
    protected $data = [];
    
    // HTTP 状态码
    protected $httpCode = 200;

    /**
     * 构造函数
     * @param string $message 错误消息
     * @param int $code 错误码
     * @param array $data 自定义数据
     * @param int $httpCode HTTP 状态码
     */
    public function __construct(string $message = '', int $code = 0, array $data = [], int $httpCode = 0)
    {
        parent::__construct($message, $code);
        
        $this->message = $message ?: $this->message;
        $this->code = $code ?: $this->code;
        $this->data = $data ?: $this->data;
        $this->httpCode = $httpCode ?: $this->httpCode;
    }

    /**
     * 获取 HTTP 状态码
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

}

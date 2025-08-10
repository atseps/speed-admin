<?php

namespace core\exception;

use core\facade\Json;

class FailedException extends BaseException
{

    /**
     * 渲染异常为 JSON 响应
     * @return \think\Response
     */
    public function render()
    {
        return Json::result($this->data, $this->code, $this->message, $this->httpCode);
    }
}

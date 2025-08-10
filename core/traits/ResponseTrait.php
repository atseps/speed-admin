<?php
namespace core\traits;

use core\facade\Json;

/**
 * 数据响应
 * trait responseTrait
 * @package core\traits
 */
 trait ResponseTrait
 {

    /**
     * 返回操作成功json
     * @param array $data
     * @param string $msg
     * @return json
     */
    protected function success($data = [] ,$msg = 'success')
    {   
        return Json::success($data, $msg);
    }


    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return json
     */
    protected function error($msg = 'error', $data = [])
    {
        return Json::error($msg, $data);
    }

 }
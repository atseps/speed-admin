<?php
namespace core\service;
use think\Response;
use think\exception\HttpResponseException;

 class JsonServer
 {
    public $json_success_code = 1;
    public $json_error_code = 0;
   
    /**
     * 返回封装后的 API 数据到客户端
     * @access protected
     * @param mixed  $data   要返回的数据
     * @param int    $code   返回的 code 成功1,失败0
     * @param mixed  $msg    提示信息
     * @param int    $http_response_code  http状态码
     * @return void
     * @throws HttpResponseException
     */
    public function result($data, $code, $msg = '', $http_response_code = 200)
    {
        $response = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data
        ];

        $response =  Response::create($response, 'json', $http_response_code);
        
        throw new HttpResponseException($response);
    }

    /** 
     * 返回操作
     * @param array $data
     * @param number $http_status_code http状态码
     * @return json
     */
    public function response($http_status_code = 200, $msg = 'success',$data = [] )
    {   
        if($http_status_code >= 200 && $http_status_code < 300){
            $json_status_code = 1;
        }else{
            $json_status_code = 0;
        }

        return $this->result($data, $json_status_code, $msg,$http_status_code);
    }


    /**
     * 返回操作成功json
     * @param array $data
     * @param string $msg
     * @return json
     */
    public function success($data = [] ,$msg = 'success')
    {   
        if(is_string($data)){
           $msg = $data;
           $data = [];
        }    
        return $this->result($data, $this->json_success_code, $msg);
    }


    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return json
     */
    public function error($msg = 'error', $data = [])
    {
         return $this->result($data, $this->json_error_code, $msg);
    }

 }
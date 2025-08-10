<?php
namespace app\adminapi\controller\system;
use core\base\BaseController;
use app\service\system\FileService;

class File extends BaseController
{

    private $service;

    function __construct(FileService $service)
    {
        parent::__construct();
        $this->service = $service;
    }


    /**
     * 上传
     * 
     * @return \think\Response
     */
    public function uploadFile(){
        $file = $this->request->file('file');
        $this->success($this->service->uploadFile($file));
    }


    /**
     * 上传图片
     * 
     * @return \think\Response
     */
    public function uploadImg(){
        $file = $this->request->file('file');
        $this->success($this->service->uploadImg($file));
    }


    /**
     * 上传附件
     * 
     * @return \think\Response
     */
    public function uploadAttachment(){
        $file = $this->request->file('file');
        $this->success($this->service->uploadAttachment($file));
    }


}

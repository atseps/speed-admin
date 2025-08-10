<?php

namespace app\service\system;

use core\base\BaseService;
use core\service\upload\UploadService;

class FileService extends BaseService
{

    private $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    /**
     * 上传
     *
     * @return \think\Response
     */
    public function uploadFile($file)
    {
        return $this->upload->checkFiles()->upload($file);
    }


    /**
     * 上传图片
     *
     * @return \think\Response
     */
    public function uploadImg($file)
    {
        return $this->upload->checkImages()->upload($file);
    }


    /**
     * 上传附件
     *
     * @return \think\Response
     */
    public function uploadAttachment($file)
    {
        return $this->upload->checkAttachment()->upload($file);
    }

}

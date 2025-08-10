<?php
namespace app\model\system;
use core\base\BaseModel;
class File extends BaseModel {
		
	 //开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    //自动写入时间戳字段
    protected $createTime = 'create_time';

}	
<?php
namespace core\base;
use think\App;
use core\traits\TransTrait;
abstract class  BaseService {

    use TransTrait;

    /**
     * 模型注入
     * @var object
     */
    protected $model;
	
	/**
	 * 错误信息
	 * @var string
	 */
	protected $error;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

	/**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct()
    {
        $this->app  = app();
        // 服务初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}
	
	
	/**
	 * 获取错误描述
	 * @return error
	 */
	public function getError() 
	{
	    return $this->error;
	}
}

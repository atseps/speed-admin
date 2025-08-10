<?php
namespace core\base;
use think\App;
use core\traits\ResponseTrait;
abstract class BaseController {

    use ResponseTrait;

	/**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];




	/**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct()
    {
        $this->app     = app();
        $this->request = $this->app->request;
        // 控制器初始化
        $this->initialize();
    }


	// 初始化
    protected function initialize()
    {}


}

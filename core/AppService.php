<?php

namespace core;

use think\Service;
use EasyWeChat\MiniProgram\Application as MiniProgram;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\Payment\Application as Payment;
use EasyWeChat\Work\Application as Work;
use EasyWeChat\OpenWork\Application as OpenWork;
use EasyWeChat\MicroMerchant\Application as MicroMerchant;
use core\service\wechat\CacheBridge;
use think\facade\Env;
/**
 * 应用服务类
 */
class AppService extends Service
{



    /**
     * 注册方法
     * 
     * @return void
     */
    public function register()
    {
        $this->registerQuery();
        $this->registerMiddleWareAlias();
        $this->registerWechat();
        $this->registerRequestMacro();
    }


    /**
     * 启动方法
     * @return void
     */
    public function boot() {
        if(Env::get('app_debug')){
            $this->commands(['install:database' => \core\command\InstallDatabase::class]);
        }
    }

    
    /**
     * register requestMacro
     * 
     * @return void
     */
    protected function registerRequestMacro()
    {
        $request = $this->app->request;
        if (!$request->hasMacro('uid')) {
            $request->macro('uid', fn() => 0);
        }
    }


    /**
     * register query
     * 
     * @return void
     */
    protected function registerQuery(): void
    {
        $connections = $this->app->config->get('database.connections');

        $connections['mysql']['query'] = BasisQuery::class;

        $this->app->config->set([
            'connections' => $connections
        ], 'database');
    }



    /**
     * register middleWares
     * @return void
     */
    protected function registerMiddleWareAlias()
    {
        $middleware = $this->app->config->get('middleware');
        //注册中间件别名
        $middleware['alias']['auth'] = [
            \app\adminapi\middleware\JwtAuth::class,
            \app\adminapi\middleware\Permissions::class,
            \app\adminapi\middleware\RecordOperate::class,
        ];
        $this->app->config->set($middleware, 'middleware');
    }


    /**
     * register wechat
     * 
     * @return void
     */
    protected function registerWechat()
    {
        //绑定类到容器中
        $apps = [
            'official_account' => OfficialAccount::class, //公众号
            'work' => Work::class, //企业微信
            'mini_program' => MiniProgram::class, //小程序
            'payment' => Payment::class, //支付
            'open_platform' => OpenPlatform::class,
            'open_work' => OpenWork::class, //企业微信第三方服务商
            'micro_merchant' => MicroMerchant::class, //小微商户
        ];
        $wechat_default = config('wechat.default') ? config('wechat.default') : [];

        foreach ($apps as $name => $app) {
            if (!config('wechat.' . $name)) {
                continue;
            }
            $configs = config('wechat.' . $name);
            foreach ($configs as $config_name => $module_default) {
                $this->app->bind('wechat.' . $name . '.' . $config_name, function ($config = []) use ($app, $module_default, $wechat_default) {
                    //合并配置文件
                    $account_config = array_merge($module_default, $wechat_default, $config);
                    $account_app = app($app, ['config' => $account_config]);
                    if (config('wechat.default.use_tp_cache')) {
                        $account_app['cache'] = app(CacheBridge::class);
                    }
                    return $account_app;
                });
            }
            if (isset($configs['default'])) {
                $this->app->bind('wechat.' . $name, 'wechat.' . $name . '.default');
            }
        }
    }
}

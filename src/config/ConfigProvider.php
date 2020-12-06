<?php

namespace flyingfiree\framework\config;

use flyingfiree\framework\core\App;
use flyingfiree\framework\core\Provider;

class ConfigProvider extends Provider
{
    //是否延迟注册
    protected $defer = true;


    //启动方法
    public function boot()
    {
        $this->app->make('Config')->load();
    }
    /**
     * 注册方法，绑定一个回调函数
     */
    public function register(App $app)
    {
        //将对象注册到服务容器里
        //绑定Database类
        $app->bind('Config', Config::class, true);
        //绑定回调函数
        // $app->bind('Database', function () {
        //     return new Database();
        // }, false);
        //绑定实例，单例模式
        // $this->app->instance('Database', new Database());
    }
}

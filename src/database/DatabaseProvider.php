<?php

namespace flyingfiree\framework\database;

use flyingfiree\framework\core\App;
use flyingfiree\framework\core\Provider;

class DatabaseProvider extends Provider
{
    //是否延迟注册
    protected $defer = true;


    //启动方法
    public function boot()
    {
        //数据库连接
        // echo 'boot';
    }
    /**
     * 注册方法，绑定一个回调函数
     */
    public function register(App $app)
    {
        //将对象注册到服务容器里
        //绑定Database类
        $app->bind('Database', Database::class);
        //绑定回调函数
        // $app->bind('Database', function () {
        //     return new Database();
        // }, false);
        //单例模式
        // $this->app->instance('Database', new Database());
    }
}

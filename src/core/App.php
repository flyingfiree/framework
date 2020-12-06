<?php

namespace flyingfiree\framework\core;

use ReflectionClass;

class App extends Container
{
    //注册的服务
    protected $serviceProviders = [];
    //延迟注册的服务
    protected $deferProviders = [];

    protected $booted = false;

    protected static $app;
    /**
     * 入口方法，启动框架，注册服务   静态方法，通过类名调用
     */
    public static function bootstrap()
    {
        define('BASE_PATH', __DIR__ . '/../..');
        $app = new self;    //创建自身的实例
        $app->bindProviders();
        $app->boot();
        self::$app = $app;  //给静态属性赋值
    }
    //返回app属性
    public static function app()
    {
        return self::$app;
    }
    /**
     * 启动服务
     */
    protected function boot()
    {
        foreach ($this->serviceProviders as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot($this);
            }
        }
        $this->booted = true;
    }
    /**
     * 读取服务，返回实例
     */
    public function make($name, $force = false)
    {
        if (!isset($this->serviceProviders[$name]) && isset($this->deferProviders[$name])) {
            $this->register($this->deferProviders[$name]);
        }
        return parent::make($name, $force);
    }
    /**
     * 注册服务
     */
    protected function bindProviders()
    {
        $config = include BASE_PATH . '/config/app.php';
        foreach ($config['providers'] as $provider) {
            //反射
            $reflection = new ReflectionClass($provider);
            $properties = $reflection->getDefaultProperties();
            if ($properties['defer'] === false) {
                //立刻注册
                $this->register(new $provider($this));
            } else {
                $alias = substr($reflection->getShortName(), 0, -8);
                //延迟注册
                $this->deferProviders[$alias] = $provider;
            }
        }
    }
    /**
     * 执行服务中的register方法
     */
    protected function register($provider)
    {
        if ($this->getProvider($provider)) {
            return;
        }
        if (is_string($provider)) {
            $provider = new $provider($this);
        }
        $provider->register($this);
        $this->serviceProviders[] = $provider;
        //只针对延迟服务
        if ($this->booted === true) {
            $provider->boot($this);
        }
    }
    /**
     * 获取已经注册的服务对象
     */
    protected function getProvider($provider)
    {
        is_object($provider) ? get_class($provider) : $provider;
        foreach ($this->serviceProviders as $instance) {
            if ($instance instanceof $provider) {
                return $instance;
            }
        }
    }
}

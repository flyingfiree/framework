<?php

namespace flyingfiree\framework\core;

abstract class Provider
{
    //是否延迟注册
    protected $defer = false;
    //抽象方法，注册对象
    abstract public function register(App $app);
    protected $app;
    /**
     * 构造函数
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }
}

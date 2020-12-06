<?php

namespace flyingfiree\framework\core;

use Closure;
use Exception;
use ReflectionClass;

class Container
{
    protected $building = [];
    //单例服务对象
    protected $instances = [];
    /**
     * 绑定已注册的服务的回调函数
     * $name    服务名
     * $closure 回调函数或者是服务类
     * $force   是否是单例
     */
    public function bind($name, $closure, $force = false)
    {
        /**
         * ['$closure' => $closure] == compact('closure')
         */
        // $this->building[$name] = ['$closure' => $closure];
        $this->building[$name] = compact('closure', 'force');
    }
    /**
     * 绑定单例对象
     */
    public function instance($name, $instance)
    {
        $this->instances[$name] = $instance;
    }
    /**
     * 读取服务，返回实例
     */
    protected function make($name, $force = false)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name]; //返回单例对象
        }
        $closure = $this->getClosure($name);
        $instance = $this->build($closure);
        //此服务是否生成单例对象
        if ($this->building[$name]['force'] === true || $force) {
            $this->instances[$name] = $instance;
        }
        return $instance;
    }
    /**
     * 返回可产生实例的回调函数
     */
    protected function getClosure($name)
    {
        return isset($this->building[$name]) ? $this->building[$name]['closure'] : $name;
    }
    /**
     * 分析类，通过回调函数生成实例
     */
    protected function build($closure)
    {
        //实现的构建是回调函数时，执行函数创建对象并返回
        if ($closure instanceof Closure) {
            return $closure($this);
        }
        //反射
        $reflection = new ReflectionClass($closure);
        $constructor = $reflection->getConstructor(); //获取构造函数
        if (is_null($constructor)) {
            return new $closure;
        }
        $parameters = $constructor->getParameters();
        $parameters = $this->parseParams($parameters);
        //使用反射，根据类实例化一个新的实例
        return $reflection->newInstanceArgs($parameters);
    }
    /**
     * 分析服务对象构造函数的参数
     */
    protected function parseParams($params)
    {
        $parameters = [];
        foreach ($params as $param) {
            $class = $param->getClass(); //获取参数的类
            if (is_null($class)) {
                //基本类型
                $parameters[] = $this->parseNonParam($param);
            } else {
                //类      实例化类，用于依赖注入
                $parameters[] = $this->build($class->name);
            }
        }
        return $parameters;
    }
    /**
     * 获取基本类型参数值
     */
    protected function parseNonParam($param)
    {
        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }
        return new Exception('参数缺少默认值');
    }
}

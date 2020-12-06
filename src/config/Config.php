<?php

namespace flyingfiree\framework\config;

class Config
{
    protected $config = [];
    public function load()
    {
        $files = glob(BASE_PATH . '/config/*');
        foreach ($files as $file) {
            $info = pathinfo($file);
            $this->config[$info['filename']] = include $file;
        }
    }
    //设置配置项的值
    public function set($name, $value)
    {
        $tmp = &$this->config;
        foreach (explode('.', $name) as $key) {
            $tmp = &$tmp[$key];
        }
        $tmp = $value;
    }
    //获取配置项
    public function get($name, $default = '')
    {
        // return $this->config[$name];
        $tmp = $this->config;
        foreach (explode('.', $name) as $key) {
            if (!isset($tmp[$key])) return $default;
            $tmp = &$tmp[$key];
        }
        return $tmp;
    }
}

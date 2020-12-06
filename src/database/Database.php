<?php

namespace flyingfiree\framework\database;

class Database
{
    public $host = 'default';
    public function __construct($name = 'abc', Config $config)
    {
        dd($config);
    }
    public function query()
    {
        echo 'database query';
    }
}

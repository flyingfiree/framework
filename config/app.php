<?php
//provider配置项

use flyingfiree\framework\config\ConfigProvider;
use flyingfiree\framework\database\DatabaseProvider;

return [
    'webname' => 'qcq.com',
    'url' => 'framework.test',
    'providers' => [
        // "flyingfiree\database\DatabaseProvider"
        DatabaseProvider::class,
        ConfigProvider::class
    ]
];

<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'runtimePath' => dirname(dirname(__DIR__)).'/runtime',
    'timezone' => 'PRC',
    'language' => 'zh-CN',
    'name' => '普安药师助手',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
           # 'cachePath' => '@frontend/runtime/cache'
        ],
        'request' => [
            'cookieValidationKey' => '35xxxsMx_h6VqgeMM9zeaaaTnss0EDsC',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
/*        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],*/
    ],
    'aliases' => [
        '@common/logic' => '@common/models/logic',
    ],
];

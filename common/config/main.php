<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'runtimePath' => dirname(dirname(__DIR__)).'/runtime',
    'timezone' => 'PRC',
    'language' => 'zh-CN',
    'name' => '普安医师助手管理系统',
    'components' => [
        'cache' => [
           // 'class' => 'yii\caching\FileCache',
            'class' => 'yii\redis\Cache',
           # 'cachePath' => '@frontend/runtime/cache'
        ],
        'request' => [
            'cookieValidationKey' => '35xxxsMx_h6VqgeMM9zeaaaTnss0EDsC',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '115.28.93.36',
            'port' => 6379,
            'database' => 0,
        ],
    ],
    'aliases' => [
        '@common/logic' => '@common/models/logic',
    ],
];

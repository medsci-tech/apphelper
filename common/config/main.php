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
        'formatter' => [
			'class' => 'yii\i18n\Formatter',
			'dateFormat' => 'php:Y-m-d',
			'datetimeFormat' => 'php:Y-m-d H:i:s',
			'timeFormat' => 'php:H:i:s',
			'nullDisplay' => '',
		]
    ],
    'aliases' => [
        '@common/logic' => '@common/models/logic',
    ],
];

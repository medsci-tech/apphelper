<?php
return [
    'components' => [
        //数据库存配置
        'db' => require(__DIR__ . '/db.php'),
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '115.28.93.36',
            'password' => 'md_root',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
        ],
        'mail' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
    ],
];

<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=115.28.93.36;dbname=app_helper',
            'username' => 'md_appHelper',
            'password' => 'md_appHelper@Db2016',
            'charset' => 'utf8',
            'tablePrefix' => 'md_',
        ],
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
    ],
];

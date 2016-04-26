<?php
/**
 * 数据库配置文件
 * @copyright Copyright (c) 2016 迈德科技
 * @author lxhui
 */
return  [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=115.28.93.36;dbname=app_helper',
    'username' => 'md_appHelper',
    'password' => 'md_appHelper@Db2016',
    'charset' => 'utf8mb4',
    'tablePrefix' => 'md_',

    'enableSchemaCache' => true,
    // Duration of schema cache.
    'schemaCacheDuration' => 3600,
    // Name of the cache component used to store schema information
    'schemaCache' => 'cache',
    // 配置从服务器
    'slaveConfig' => [
        'username' => 'slave',
        'password' => '',
        'attributes' => [
            // use a smaller connection timeout
            PDO::ATTR_TIMEOUT => 10,
        ],
    ],
    // 配置从服务器组
    'slaves' => [
        ['dsn' => 'dsn for slave server 1'],
        ['dsn' => 'dsn for slave server 2'],
        ['dsn' => 'dsn for slave server 3'],
        ['dsn' => 'dsn for slave server 4'],
    ],
];

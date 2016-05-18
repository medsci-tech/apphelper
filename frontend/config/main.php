<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => '医师助手',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'aliases' => [
        '@views' => dirname(__DIR__) . "/views/",
        '@jsUrl' => Yii::$app->request->baseUrl."/js/",
        '@cssUrl' => Yii::$app->request->baseUrl."/css/"
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            //'baseUrl' => '//cdn.yunjianyi.com/assets', //使用cdn
            'baseUrl' => '/assets',
            'bundles' => [
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
               // '<account:\w+>/<action:\w+>' => '<account>/<action>',
            ),
        ],
/*	    'xunsearch' => [
            'class' => 'hightman\xunsearch\Connection', // 此行必须
            'iniDirectory' => '@app/config',    // 搜索 ini 文件目录，默认：@vendor/hightman/xunsearch/app
            'charset' => 'utf-8',   // 指定项目使用的默认编码，默认即时 utf-8，可不指定
        ],*/

        // 'authClientCollection' => [
        //     'class' => 'yii\authclient\Collection',
        //     'clients' => [
        //         'qq' => [
        //             'class' => 'xj\oauth\QqAuth',
        //             'clientId' => '101222501',
        //             'clientSecret' => '61a94f74aee16b274bff371c1a72edd3'

        //         ],
        //         'sina' => [
        //             'class' => 'xj\oauth\WeiboAuth',
        //             'clientId' => '111',
        //             'clientSecret' => '111',
        //         ],
        //         'weixin' => [
        //             'class' => 'xj\oauth\WeixinAuth',
        //             'clientId' => '111',
        //             'clientSecret' => '111',
        //         ],
        //     ]
        // ],
    ],
    'params' => $params,
];

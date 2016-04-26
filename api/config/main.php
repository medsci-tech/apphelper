<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
    #require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //'controllerNamespace' => 'api\common\controllers',
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'pVvCRkQNUEUkz5Q2LBL99dW95yyAQbQs',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
           // 'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
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
        'urlManager' => [
            'enablePrettyUrl' => true,// 启用美化URL
            //'enableStrictParsing' => true, // 是否执行严格的url解析
            'showScriptName' => false,// 在URL路径中是否显示脚本入口文件
           // 'rules' => require(__DIR__ . '/restful.php'),
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => ['v4/article','v4/site'],
                    'pluralize' => false,
                    'extraPatterns' => [
                    ]
                ],
            ],
        ],
    ],
    'modules' => [
        'v4' => [
            'basePath' => '@api/modules/v4',
            'class' => api\modules\v4\Module::className()
        ],
    ],
    'params' => $params
];

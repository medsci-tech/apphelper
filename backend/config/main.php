<?php

$params = array_merge(
    require(__DIR__.'/../../common/config/params.php'),
    require(__DIR__.'/../../common/config/params-local.php'),
    require(__DIR__.'/params.php')
    #require(__DIR__.'/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'on afterLogin' => function($event) {
                $user = $event->identity;
                $user->updated_at = time(); //登录后更新最后登录时间
                $user->save();
            }
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'md_auth_item',
            'assignmentTable' => 'md_auth_assignment',
            'itemChildTable' => 'md_auth_item_child',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'forceTranslation' => true,
                ],
                'kvgrid' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@kvgrid/messages',
                    'forceTranslation' => true,
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-green',
                ],
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            'i18n' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@kvgrid/messages',
                'forceTranslation' => true,
            ],
        ],
        'database' => [
            'class' => 'database\Module'
        ]
    ],
    'aliases' => [
        '@mdm/admin' => '@backend/mdmsoft/yii2-admin',
        '@kvgrid' => '@vendor/kartik-v/yii2-grid',
        '@database' => '@backend/database'
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            '*',
        ],
    ],
    'params' => $params,
];

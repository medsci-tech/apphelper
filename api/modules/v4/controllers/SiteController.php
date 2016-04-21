<?php

namespace api\modules\v4\controllers;

use common\logic\Article;
use yii\rest\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public $modelClass = 'api\modules\v4\models\article';//Yii::$app->getRequest()->getBodyParams()['newsItem'];
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;

    }
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
    public function actionLogin()
    {
        $apiParams = $_POST['apiParams'];

       // $apiParams = json_encode(['uid'=>100,'nickname'=>'mary','access_token' => 'absgfjfj#$48667JUY65']); //{"uid":100,"nickname":"mary","access_token":"absgfjfj#$48667JUY65"}
        $apiParams = json_decode($apiParams,true);
        $code = '-1';
        if(!$apiParams['username'] || !$apiParams['password'])
        {
            $result = ['code' => $code,'message'=>'用户密码不能为空','data'=>[]];
        }
        else{
            $result = ['code' => '200','message'=>'登录成功','data'=>['uid'=>100,'nickname'=>'mary','access_token' => 'absgfjfj#$48667JUY65']];
        }
        return $result;
    }

    public function actionSign()
    {
        $apiParams = $_POST['apiParams'];
        $apiParams = json_decode($apiParams,true);
        $code = '-1';
        if(!$apiParams['username'] || !$apiParams['password']  || !$apiParams['veryCode'])
        {
            $result = ['code' => $code,'message'=>'用户名或密码不能为空','data'=>[]];

        }
        else
            $result = ['code' => '200','message'=>'注册成功','data'=>['uid'=>101,'nickname'=>'mary01','access_token' => 'absgfjfj#$48667JUY65']];
            return $result;
    }
}
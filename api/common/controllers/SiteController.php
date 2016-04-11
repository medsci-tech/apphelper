<?php

namespace api\common\controllers;

use common\logic\Article;
use yii\rest\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }
    public function actionIndex()
    {
        return Article::find()->limit(1)->one();
    }

    public function actionTest()
    {
        return ['code'=>200,'','data'=>['a','b','c']];
    }
}
<?php

namespace api\common\controllers;

use common\logic\Article;
use yii\rest\Controller;
use yii\web\Response;

class LoginController extends Controller
{

    public function actionIndex()
    {
        return Article::find()->limit(1)->one();
    }

    public function actionTest()
    {
        return ['code'=>200,'','data'=>['a','b','c']];
    }
}
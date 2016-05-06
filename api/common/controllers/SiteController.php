<?php

namespace api\common\controllers;

use common\logic\Article;

class SiteController extends Controller
{

    public function actionIndex()
    {
        echo('here');
        return Article::find()->limit(1)->one();
    }

    public function actionLogin()
    {
       echo'login';exit;
    }

    public function actionTest()
    {
        return ['code'=>200,'','data'=>['a','b','c']];
    }
}
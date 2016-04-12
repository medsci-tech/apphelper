<?php

namespace api\common\controllers;

use api\common\controllers;
use common\models\Member;
use yii\web\Response;

class PublicController extends Controller
{

    public function actionLogin()
    {
        $request = \Yii::$app->request->get();
        $this->setPassword($this->password);
        $this->ajaxReturn(['code','smg',[1,2,3,4,5]]);

    }

    public function actionTest()
    {
        return ['code'=>200,'','data'=>['a','b','c']];
    }
}
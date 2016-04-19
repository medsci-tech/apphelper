<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Backend controller
 */
class BackendController extends Controller
{
    public $layout = 'main-common';
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        //未登录
        if (\Yii::$app->user->isGuest) {
            //Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'status' => -1,
                'message' => '请先登录',
                'url' => Yii::$app->getHomeUrl()
            );
            return $this->goHome();
        }

        return true; // or false to not run the action
    }

}


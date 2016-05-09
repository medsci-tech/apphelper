<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 16:08
 */

namespace backend\controllers;

use common\models\AD;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AdController extends BackendController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
<?php

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class HospitalController extends Controller
{

    /**
     * Lists all Article models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [

        ]);
    }


}

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
        $strHtml = $this->getHtmlImage();
        return $this->render('index',[
            'model' => new AD(),
            'strHtml' => $strHtml,
        ]);
    }

    public function actionCreate()
    {

    }


    public  function actionResource()
    {
        return $this->render('resource');
    }

    protected function getHtmlImage()
    {
        $strHtml = "";
        $allAD = AD::find()
            ->orderBy('id')
            ->all();

        foreach($allAD as $ad)
        {
            $strHtml = $strHtml." <div class='col-sm-6 col-md-4'>
                <img src='".$ad->imgurl."' class='thumbnail'
                aid='".$ad->id."' sort='".$ad->sort."
                links='".$ad->linkurl."'  status ='".$ad->status."'
                attr_id='".$ad->attr_id."' attr_from ='".$ad->attr_from."'
                '></div> ";
        }

        return $strHtml;
    }

}
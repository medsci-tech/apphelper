<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 16:08
 */

namespace backend\controllers;

use common\models\AD;
use common\models\Exam;
use common\models\Resource;
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
        $strHtml = '';
        $allResource = Exam::find()->all();
        foreach($allResource as $resource){
            $strHtml = $strHtml."<tr>
                    <td>".$resource->id."</td>
                    <td>".$resource->about."</td>
                    </tr>";
        }
        return $this->render('resource', [
            'strHtml' => $strHtml
        ]);
    }

    public function actionFind()
    {
        $strHtml = '';
        $params = Yii::$app->request->get();
        if ('1' == $params['optionsResource']) {
            if ($params['resource']) {
                $allResource = Resource::find()->all();
            } else {
                $allResource = Resource::find()->all();
            }
            foreach ($allResource as $resource) {
                $strHtml = $strHtml . "<tr>
                    <td>" . $resource->id . "</td>
                    <td>" . $resource->title . "</td>
                    </tr>";
            }
        }

        if ('2' == $params['optionsResource']) {
            if ($params['resource']) {
                $allExam = Exam::find()->all();
            } else {
                $allExam = Exam::find()->all();
            }
            foreach ($allExam as $exam) {
                $strHtml = $strHtml . "<tr>
                    <td>" . $exam->id . "</td>
                    <td>" . $exam->about . "</td>
                    </tr>";
            }
        }


        return $this->render('resource', [
            'strHtml' => $strHtml
        ]);
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
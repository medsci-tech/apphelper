<?php

namespace backend\controllers;

use common\models\ArticleData;
use yidashi\webuploader\WebuploaderAction;
use Yii;
use common\models\ResourceClass;
use backend\models\search\Article as ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ResourceClassController extends BackendController
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

    /**
     * Lists all Article models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $strHtml = $this->getTreeMenu();
        print($strHtml);
        return $this->render('index', [
            'strHtml' => $strHtml,
        ]);
    }


    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return Article the loaded model
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getTreeMenu()
    {
        $strHtml = "<ul>";
        $parents = ResourceClass::find()
            ->where(['grade' => 1])
            ->orderBy('id')
            ->all();

        foreach($parents as $parent)
        {
            $childLevel1s = ResourceClass::find()
                ->where(['parent' => $parent->id])
                ->orderBy('id')
                ->all();

            if($childLevel1s)
            {
                $strHtml = $strHtml."<li>".$parent->name."<ul>";
                foreach($childLevel1s as $childLevel1)
                {
                    $childLevel2s = ResourceClass::find()
                        ->where(['parent' => $childLevel1->id])
                        ->orderBy('id')
                        ->all();

                    if($childLevel2s)
                    {
                        $strHtml = $strHtml."<li>".$childLevel1->name."<ul>";
                        foreach($childLevel2s as $childLevel2)
                        {
                            $strHtml = $strHtml."<li>".$childLevel2->name."</li>";
                        }
                        $strHtml = $strHtml."</ul></li>";
                    }
                    else
                    {
                        $strHtml = $strHtml."<li>".$childLevel1->name."</li>";
                    }
                }
                $strHtml = $strHtml."</ul></li>";
            }
            else
            {
                $strHtml = $strHtml."<li>".$parent->name."</li>";
            }
        }

        $strHtml = $strHtml."</ul>";

        return $strHtml;
    }
}

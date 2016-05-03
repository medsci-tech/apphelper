<?php

namespace backend\controllers;

use Yii;
use common\models\ResourceClass;
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
//        print($strHtml);
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
        if (($model = ResourceClass::findOne($id)) !== null) {
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
                $strHtml = $strHtml."<li uid='".$parent->id."' grade='"
                    .$parent->grade."'>"
                    .$parent->name."<ul>";
                foreach($childLevel1s as $childLevel1)
                {
                    $childLevel2s = ResourceClass::find()
                        ->where(['parent' => $childLevel1->id])
                        ->orderBy('id')
                        ->all();

                    if($childLevel2s)
                    {
                        $strHtml = $strHtml."<li uid='".$childLevel1->id."' grade='"
                            .$childLevel1->grade."'>"
                            .$childLevel1->name."<ul>";
                        foreach($childLevel2s as $childLevel2)
                        {
                            $strHtml = $strHtml."<li uid='".$childLevel2->id."' grade='"
                                .$childLevel2->grade."'>"
                                .$childLevel2->name."</li>";
                        }
                        $strHtml = $strHtml."</ul></li>";
                    }
                    else
                    {
                        $strHtml = $strHtml."<li uid='".$childLevel1->id."' grade='"
                            .$childLevel1->grade."'>"
                            .$childLevel1->name."</li>";
                    }
                }
                $strHtml = $strHtml."</ul></li>";
            }
            else
            {
                $strHtml = $strHtml."<li uid='".$parent->id."' grade='"
                    .$parent->grade."'>"
                    .$parent->name."</li>";
            }
        }

        $strHtml = $strHtml."</ul>";

        return $strHtml;
    }

    public function actionOption()
    {
        $params = Yii::$app->request->post();
        if('addable' == $params['type']){
            if ('0' == $params['uid']) {
                $model = new ResourceClass();
                $model->name = $params['resource_name'];
                $model->grade = 1;
                $model->parent = 0;
                $model->status = 1;
                $model->uid = 0;
                $model->attr_type = 0;
                $model->sort = 0;
                $model->save(false);
            }
            else {
                $model = new ResourceClass();
                $model->name = $params['resource_name'];
                $model->grade = $params['grade'] + 1;
                $model->parent = $params['uid'];
                $model->status = 1;
                $model->uid = 0;
                $model->attr_type = 0;
                $model->sort = 0;
                $model->save(false);
            }
            return $this->redirect(['index']);
        }else if('editable' == $params['type']){

            $model = $this->findModel($params['uid']);
            $model ->name = $params['resource_name'];
            $model ->save(false);

            return $this->redirect(['index']);
        } else{
            return $this->redirect(['index']);
        }
    }
}

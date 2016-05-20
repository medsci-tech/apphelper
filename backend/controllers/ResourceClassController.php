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
        $type = 0;
        $strHtml = $this->getTreeMenu($type);
//        print($strHtml);
        return $this->render('index', [
            'strHtml' => $strHtml,
        ]);
    }

    public function actionCustom()
    {
        $type = 1;
        $strHtml = $this->getTreeMenu($type);
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

    protected function getTreeMenu($type)
    {
        $strHtml = "<ul>";
        $parents = ResourceClass::find()
            ->where(['grade' => 1, 'attr_type' => $type])
            ->orderBy('id')
            ->all();

        foreach($parents as $parent)
        {
            $childLevel1s = ResourceClass::find()
                ->where(['parent' => $parent->id])
                ->orderBy('sort')
                ->all();

            if($childLevel1s)
            {
                $strHtml = $strHtml."<li uid='".$parent->id."' sort='".$parent->sort."' grade='"
                    .$parent->grade."'>"
                    .$parent->name."<ul>";
                foreach($childLevel1s as $childLevel1)
                {
                    $childLevel2s = ResourceClass::find()
                        ->where(['parent' => $childLevel1->id])
                        ->orderBy('sort')
                        ->all();

                    if($childLevel2s)
                    {
                        $strHtml = $strHtml."<li uid='".$childLevel1->id."' sort='"
                            .$childLevel1->sort."'grade='"
                            .$childLevel1->grade."'>"
                            .$childLevel1->name."<ul>";
                        foreach($childLevel2s as $childLevel2)
                        {
                            $strHtml = $strHtml."<li uid='".$childLevel2->id."' sort='"
                                .$childLevel2->sort."'grade='"
                                .$childLevel2->grade."'>"
                                .$childLevel2->name."</li>";
                        }
                        $strHtml = $strHtml."</ul></li>";
                    }
                    else
                    {
                        $strHtml = $strHtml."<li uid='".$childLevel1->id."' sort='"
                            .$childLevel1->sort."' grade='"
                            .$childLevel1->grade."'>"
                            .$childLevel1->name."</li>";
                    }
                }
                $strHtml = $strHtml."</ul></li>";
            }
            else
            {
                $strHtml = $strHtml."<li uid='".$parent->id."' sort='"
                    .$parent->sort."' grade='"
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
                $model->sort = 0;
                $model->attr_type = 0;
                $model->path =',';
                $model->save(false);

                $model->path = $model->path.$model->id.',';
                $model->save(false);
            }
            else {
                $parent = $this->findModel($params['uid']);
                $model = new ResourceClass();
                $model->name = $params['resource_name'];
                $model->grade = $params['grade'] + 1;
                $model->parent = $params['uid'];
                $model->status = 1;
                $model->uid = 0;
                $model->sort = 0;
                $model->attr_type = 0;
                $model->path = $parent->path;
                $model->save(false);

                $model->path = $model->path.$model->id.',';
                $model->save(false);
            }
            return $this->redirect(['index']);
        } else if('editable' == $params['type']) {

            $model = $this->findModel($params['uid']);
            $model -> name = $params['resource_name'];
            $model -> sort = $params['sort'];
            $model -> save(false);

            return $this->redirect(['index']);
        } else if('enable' == $params['type']) {
            $model = $this->findModel($params['uid']);
            $model -> status = 1;
            $model -> save(false);
            if($model -> grade != 3) {
                $childs = ExamClass::find()
                    ->andFilterWhere(['like', 'path', $model->id])
                    ->all();
                foreach($childs as $child)
                {
                    if($child->id != $model->id) {
                        $child->status = 1;
                        $child->save(false);
                    }
                }
            }
            return $this->redirect(['index']);
        } else if('disable' == $params['type']) {
            $model = $this->findModel($params['uid']);
            $model -> status = 0;
            $model -> save(false);
            if($model -> grade != 3) {
                $childs = ExamClass::find()
                    ->andFilterWhere(['like', 'path', $model->id])
                    ->all();
                foreach($childs as $child)
                {
                    if($child->id != $model->id) {
                        $child->status = 0;
                        $child->save(false);
                    }
                }
            }
            return $this->redirect(['index']);
        } else{
            return $this->redirect(['index']);
        }
    }
}

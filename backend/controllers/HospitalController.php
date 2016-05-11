<?php

namespace backend\controllers;
use frontend\controllers\FrontendController;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\search\Hospital as HospitalSearch;
use common\models\Hospital;
use yii\data\ActiveDataProvider;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class HospitalController extends BackendController
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
        $appYii = Yii::$app;
        $searchModel = new HospitalSearch();
        $dataProvider = $searchModel->search($appYii->request->queryParams);
        return $this->render('index', [
            'model' => new Hospital(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $id = Yii::$app->request->post('Hospital')['id'];
        if($id) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
//                return $this->redirect(['view', 'id' => $model->id]);
            } else {
//                return $this->render('update', [
//                    'model' => $model,
//                ]);
            }
        }else {
            $model = new Hospital();
            if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
//                return $this->redirect(['view', 'id' => $model->id]);
            } else {
//                return $this->render('create', [
//                    'model' => $model,
//                ]);
            }
        }
        return $this->redirect(['index']);

    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Hospital::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionModify()
    {
        $params = Yii::$app->request->post();
        if('disable' == $params['type']){
            /*禁用*/
            foreach ($params['selection'] as $key => $val){
                $model = $this->findModel($val);
                $model->status = 0;
                $model->save(false);
            }
        }else if('enable' == $params['type']){
            /*启用*/
            foreach ($params['selection'] as $key => $val){
                $model = $this->findModel($val);
                $model->status = 1;
                $model->save(false);
            }
        } elseif ('del' == $params['type']) {
            /*删除*/
            foreach ($params['selection'] as $key => $val) {
                $this->findModel($val)->delete();
            }
        }
        return $this->redirect(['index']);
    }
}

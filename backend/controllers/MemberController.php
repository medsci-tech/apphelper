<?php

namespace backend\controllers;

use common\models\ArticleData;
use common\models\Member;
use yidashi\webuploader\WebuploaderAction;
use Yii;
use common\logic\Article;
use backend\models\search\Article as ArticleSearch;
use backend\models\search\Hospital as HospitalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class MemberController extends Controller
{

    /**
     * Lists all Article models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchMember = new \backend\models\search\Member();
        $searchModel = new HospitalSearch();
//        $dataProvider = $searchMember->findAll(Yii::$app->request->queryParams);
        $dataProvider = $searchMember->search(Yii::$app->request->queryParams);
//var_dump(Yii::$app->params['member']['rank']);exit;
        return $this->render('index', [
            'searchModel' => $searchMember,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member();
        if ($model->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
            if ($isValid) {
                $model->save(false);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
            if ($isValid) {
                $model->save(false);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

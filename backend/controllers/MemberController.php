<?php

namespace backend\controllers;

use common\models\Hospital;
use common\models\Member;
use common\models\Region;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
        $dataProvider = $searchMember->search(Yii::$app->request->queryParams);
        $memberRank = Yii::$app->params['member'];
        return $this->render('index', [
            'searchModel' => $searchMember,
            'dataProvider' => $dataProvider,
            'memberRank' => $memberRank,
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
        $member = $this->findModel($id);
        $member->hospital_id = Hospital::findOne($member->hospital_id)->name;
        $member->rank_id = Yii::$app->params['member']['rank'][$member->rank_id];
        $member->province_id =  Region::findOne($member->province_id)->name;
        $member->city_id =  Region::findOne($member->city_id)->name;
        $member->area_id =  Region::findOne($member->area_id)->name;
        return $this->render('view', [
            'model' => $member,
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
                $model->created_at = time();
                $res = $model->save(false);
                if($res){
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect(['create']);
                }
            }else{
                return $this->redirect(['create']);
            }
        }else{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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
//            var_dump($model->province_id);exit;
            if ($isValid) {
                $model->updated_at = time();
                $res = $model->save(false);
                if($res){
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect(['update?id=' . $id]);
                }
            }else{
                return $this->redirect(['update?id=' . $id]);
            }
        }else{
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

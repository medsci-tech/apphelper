<?php

namespace backend\controllers;

use common\models\Exercise;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ExerciseController extends BackendController
{

    /**
     *
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $searchMember = new Exercise();
        $dataProvider = $searchMember->search($appYii->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchMember,
            'dataProvider' => $dataProvider,
            'params' => $appYii->params,
        ]);
    }

    public function actionForm()
    {
        $appYii = Yii::$app;
        $exercise = new Exercise();
//        $exercise->load($appYii->request->post());
//        $isValid = $exercise->validate();
        return $this->render('_form',[
            'model' => $exercise,
        ]);
        var_dump($appYii->request->post());exit;
        if ($isValid) {
            $this->created_at = time();
            $this->update_at = time();
            $res = $this->save(false);
            return $res;
        }else{
            return false;
        }


        var_dump($res);
    }


    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        if(isset($params['selection'])) {
            if ('disable' == $params['type']) {
                /*禁用*/
                foreach ($params['selection'] as $key => $val) {
                    $member = $this->findModel($val);
                    $member->status = 0;
                    $member->save(false);
                }
            } elseif ('enable' == $params['type']) {
                /*启用*/
                foreach ($params['selection'] as $key => $val) {
                    $member = $this->findModel($val);
                    $member->status = 1;
                    $member->save(false);
                }
            } elseif ('del' == $params['type']) {
                /*删除*/
                foreach ($params['selection'] as $key => $val) {
                    $this->findModel($val)->delete();
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Exercise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

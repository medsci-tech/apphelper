<?php

namespace backend\controllers;

use backend\models\search\Exam as ExamSearch;
use common\models\Exam;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ExamController extends BackendController
{

    /**
     *
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $search = new ExamSearch();
        $dataProvider = $search->search($appYii->request->queryParams);
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'params' => $appYii->params,
        ]);
    }

    public function actionForm()
    {
        $appYii = Yii::$app;
        if(isset($appYii->request->get()['id'])){
            $id = $appYii->request->get()['id'];
            $model = $this->findModel($id);
            if(empty($model)){
                $model = new Exam();
            }
        }else{
            $model = new Exam();
        }
        $model->load($appYii->request->post());
        $isValid = $model->validate();
        if ($isValid) {
            $optionArray = [];
            foreach ($model->option as $key => $val){
                $optionArray[chr(65 + $key)] = $val;
            }
            $model->option = serialize($optionArray);
            $model->answer = implode(',', $model->answer);
            if(isset($model->id)){
                $model->update_at = time();
            }else{
                $model->created_at = time();
            }
            $result = $model->save(false);
            if($result){
                $return = [200,'success'];
            }else{
                $return = [801,'save error'];
            }
        }else{
            $return = [802,'validate error'];
        }
        $this->ajaxReturn($return);
    }


    public function actionDelete()
    {
        $appYii = Yii::$app;
        $params = $appYii->request->post();
        $id = $appYii->request->get('id');
        if(isset($params['selection'])) {
            if ('del' == $params['type']) {
                /*删除*/
                foreach ($params['selection'] as $key => $val) {
                    $this->findModel($val)->delete();
                }
            }elseif ('enable' == $params['type']) {
                /*启用*/
                (new Exam())->saveData(['id' => $params['selection']], ['status' => 1]);
            } elseif ('disable' == $params['type']) {
                /*禁用*/
                (new Exam())->saveData(['id' => $params['selection']], ['status' => 0]);
            } elseif ('isPub' == $params['type']) {
                /*发布*/
                (new Exam())->saveData(['id' => $params['selection']], ['publish_status' => 1]);
            } elseif ('noPub' == $params['type']) {
                /*取消发布*/
                (new Exam())->saveData(['id' => $params['selection']], ['publish_status' => 0]);
            } elseif ('isRec' == $params['type']) {
                /*推荐*/
                (new Exam())->saveData(['id' => $params['selection']], ['recommend_status' => 1]);
            } elseif ('noRec' == $params['type']) {
                /*取消推荐*/
                (new Exam())->saveData(['id' => $params['selection']], ['recommend_status' => 0]);
            }
        }elseif($id){
            $this->findModel($id)->delete();
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
        if (($model = Exam::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

}

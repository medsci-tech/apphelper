<?php

namespace backend\controllers;

use common\models\Exercise;
use common\models\ExamClass;
use Yii;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

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
        $search = new Exercise();
        $dataProvider = new ActiveDataProvider([
            'query' => $search->search($appYii->request->queryParams)->query,
            'pagination' => [
                'pageSize' => $appYii->params['pageSize'],
            ]
        ]);
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
            $exercise = $this->findModel($id);
            if(empty($exercise)){
                $exercise = new Exercise();
            }
        }else{
            $exercise = new Exercise();
        }
        $exercise->load($appYii->request->post());
        $isValid = $exercise->validate();
        if ($isValid) {
            $optionArray = [];
            foreach ($exercise->option as $key => $val){
                $optionArray[chr(65 + $key)] = $val;
            }
            $exercise->option = serialize($optionArray);
            $exercise->answer = implode(',', $exercise->answer);
            if(isset($exercise->id)){
                $exercise->update_at = time();
            }else{
                $exercise->created_at = time();
            }
            $result = $exercise->save(false);
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
        if (($model = Exercise::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    protected function treeNavigate(){
       

    }
}

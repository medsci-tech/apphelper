<?php

namespace backend\controllers;

use backend\models\search\Exercise as ExerciseSearch;
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
        $search = new ExerciseSearch();
        $examClass = new ExamClass();
        $recursionTree = $examClass->recursionTree();
        $examClassFindOne = [];
        if(isset($appYii->request->queryParams['Exercise']['category'])){
            $examClassFindOne = $examClass->getDataForWhere(['id' => $appYii->request->queryParams['Exercise']['category']]);
        }
        $dataProvider = $search->search($appYii->request->queryParams);
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'params' => $appYii->params,
            'examClass' => json_encode($recursionTree),
            'treeNavigateSelectedName' => $examClassFindOne[0]['name'] ?? '',
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
                $return = ['success','操作成功哦'];
            }else{
                $return = ['error', '操作失败哦'];
            }
        }else{
            $return = ['error', '操作失败哦'];
        }
        Yii::$app->getSession()->setFlash($return[0], $return[1]);
        $this->redirect('index');
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

    /**
     * @return mixed
     */
    public function actionView($id)
    {
        $params = Yii::$app->params;
        $model = $this->findModel($id);
        $model->category = $model->category ? ExamClass::findOne($model->category)->name : '';
        $option = unserialize($model->option);
        $optionTemp = '';
        if($option){
            foreach ($option as $key => $val){
                $optionTemp .= $key . ':' . $val . '; ';
            }
        }
        $model->option = $optionTemp;
        $model->type = $params['exercise']['type'][$model->type];
        $model->status =  $params['statusOption'][$model->status];
        return $this->render('view', [
            'model' => $model,
        ]);
    }

}

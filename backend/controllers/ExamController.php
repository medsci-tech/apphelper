<?php

namespace backend\controllers;

use backend\models\search\Exam as ExamSearch;
use common\models\Exam;
use common\models\ExamClass;
use common\models\ExamLevel;
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
        $model->load(['Exam' => $appYii->request->post()['Exam']]);
        $isValid = $model->validate();
        if ($isValid) {
            $model->exe_ids = implode(',', $model->exe_ids);
            if(!isset($model->id)){
                $model->created_at = time();
            }
            if($appYii->request->post()['Exam']['imgUrl']){
                $model->imgUrl = $appYii->request->post()['Exam']['imgUrl'];
            }
            $result = $model->save(false);
            if($result){
                $examLevelModel = new ExamLevel();
                $examLevelDel = [];
                $examLevelRequest = $appYii->request->post()['ExamLevel'];
                if(is_array($examLevelRequest)){
                    $examLevelData = [];
                    $examLevelDel = $examLevelRequest['id'];
                    foreach ($examLevelRequest as $key => $val){
                        foreach ($val as $k => $v){
                            $examLevelData[$k][$key] = $v;
                            $examLevelData[$k]['exam_id'] = $model->id;
                        }
                    }
                    if(is_array($examLevelData)){
                        foreach ($examLevelData as $key => $val){
                            if($val['id']){
                                $examLevelModel = ExamLevel::findOne($val['id']);
                            }else{
                                $examLevelModel = new ExamLevel();
                            }
                            $examLevelModel->load(['ExamLevel' => $val]);
                            $examLevelModel->save(false);
                            $examLevelDel[] = $examLevelModel->id;
                        }
                    }
                }
                $examLevelModel->deleteAll(['and', 'exam_id=' . $model->id, ['not in', 'id', $examLevelDel]]);

                $return = ['success', '操作成功哦'];
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
                (new Exam())->saveData(['id' => $params['selection']], ['publish_status' => 1, 'publish_time' => time()]);
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

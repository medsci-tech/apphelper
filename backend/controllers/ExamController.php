<?php

namespace backend\controllers;

use backend\models\search\Exam as ExamSearch;
use common\models\Exam;
use common\models\ExamClass;
use common\models\ExamLevel;
use common\models\Exercise;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ExamController extends BackendController
{

    public function actionIndex()
    {
        $appYii = Yii::$app;
        /*获取试题分类的树形结构*/
        $examClassModel = new ExamClass();
        $examClassData = $examClassModel->getDataForWhere();
        $tree = new TreeController($examClassData, '&nbsp;|-&nbsp;');
        $examClassTree = $tree->get_tree('id', 'name');
        /*查询试卷*/
        $search = new ExamSearch();
        $dataProvider = $search->search($appYii->request->queryParams);
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'examClassTree' => $examClassTree,
        ]);
    }

    public function actionForm()
    {
        $appYii = Yii::$app;
        $get = $appYii->request->get();
        $post = $appYii->request->post();
        if(isset($get['id'])){
            //有id修改
            $id = $get['id'];
            $model = $this->findModel($id);
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][7].$id); //删除缓存
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][4].$id);//删除缓存
            if(empty($model)){
                $model = new Exam();
            }
        }else{
            //无id添加
            $model = new Exam();
        }
        $model->load(['Exam' => $post['Exam']]);
        $isValid = $model->validate();
        if ($isValid) {
            if(1 == $post['Exam']['type']){
//                /*type=1随机分配试题*/
                $model->exe_ids = '';
            }else{
                /*type=0自定义分配试题*/
                $model->exe_ids = implode(',', array_unique($post['Exam']['exe_ids']));
                $model->class_id = null;
                $model->total = '';
            }
            if(!isset($model->id)){
                $model->created_at = time();
            }
            $result = $model->save(false);
            if($result){
                //评分规则
                $examLevelModel = new ExamLevel();
                $examLevelDel = [];
                $examLevelRequest = $post['ExamLevel'];
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
                                $examLevelModel = ExamLevel::findOne($val['id']);//有id修改
                            }else{
                                $examLevelModel = new ExamLevel();//无id添加
                            }
                            $examLevelModel->load(['ExamLevel' => $val]);
                            $examLevelModel->save(false);
                            $examLevelDel[] = $examLevelModel->id;
                        }
                    }
                }
                $examLevelModel->deleteAll(['and', 'exam_id=' . $model->id, ['not in', 'id', $examLevelDel]]);//删除评分规则
                $return = ['code' => 200, 'msg' => '', 'data' => ''];
            } else {
                $return = ['code' => 801, 'msg' => '服务端操作失败', 'data' => ''];
            }
        }else{
            $return = ['code'=>802,'msg'=>'数据有误','data'=>''];
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
        
        /* 删除缓存 */
        foreach ($params['selection'] as $key => $val) {
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][7].$val);
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][4].$val);
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

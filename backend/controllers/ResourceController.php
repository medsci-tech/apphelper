<?php

namespace backend\controllers;

use backend\models\search\Resource as ResourceSearch;
use common\models\Resource;
use common\models\ResourceClass;
use common\models\User;
use Yii;
use yii\web\Cookie;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ResourceController extends BackendController
{

    /**
     * 自定义培训列表页
     * @author zhaiyu
     * @return string
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;

        /*带搜索的目录树形结构*/
        $resourceClassModel = new ResourceClass();
        $directoryStructureSearch = $resourceClassModel->recursionTree(0,['attr_type' => 1]);//获取自定义分类树形图
        $treeNavigateSelected = [];
        if(isset($appYii->request->queryParams['Resource']['rid'])){
            $treeNavigateSelected = $resourceClassModel->getDataForWhere(['id' => $appYii->request->queryParams['Resource']['rid']]);
        }

        /*条件查询*/
        $search = new ResourceSearch();
        $dataProvider = $search->search($appYii->request->queryParams, 1);

        /*导出数据处理*/
        $exportData = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $exportData[$key]['title'] = $val->title;
            $exportData[$key]['author'] = $val->author;
            $exportData[$key]['views'] = $val->views;
            $exportData[$key]['comments'] = $val->comments;
            $exportData[$key]['uid'] = $val->uid ? User::find()->where(['id' => $val->uid])->one()->username : '';
            $exportData[$key]['recommend_status'] = $appYii->params['recStatusOption'][$val->recommend_status];
            $exportData[$key]['publish_status'] =$appYii->params['pubStatusOption'][$val->publish_status];
            $exportData[$key]['status'] = $appYii->params['statusOption'][$val->status];
            $exportData[$key]['publish_time'] = $val->publish_status == 1 ? date('Y-m-d H:i:s', $val->publish_time) : '';
            $exportData[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);
        }

        /*将数据存入cookie以便导出*/
        $cookie = $appYii->response->cookies->add(new Cookie([
            'name' => 'resourceDataExportToExcel',
            'value' => json_encode($exportData),
        ]));
//var_dump($cookie);exit;
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'directoryStructureSearch' => json_encode($directoryStructureSearch),
            'treeNavigateSelectedName' => $treeNavigateSelected[0]['name'] ?? '',
        ]);
    }

    /**
     * 自定义培训保存
     * @author zhaiyu
     */
    public function actionForm()
    {
        $post = Yii::$app->request->post();
        $return = $this->CommonSave($post);
        Yii::$app->getSession()->setFlash($return[0], $return[1]);
        $this->redirect('index');
    }

    /**
     * 培训保存公共方法
     * @author zhaiyu
     * @param $post
     * @return array
     */
    public function CommonSave($post)
    {
        if(isset($post['Resource']['id'])){
            //有id修改
            $id = $post['Resource']['id'];
            $model = $this->findModel($id);
            if(empty($model)){
                $model = new Resource();
            }
        }else{
            //无id添加
            $model = new Resource();
        }
        $model->load($post);
        $isValid = $model->validate();
        if ($isValid) {
            if(!isset($model->id)){
                $model->created_at = time();
            }
            if($post['Resource']['imgurl']){
                $model->imgurl = $post['Resource']['imgurl'];
            }
            $model->rids = implode(',', array_unique($model->rids));
            $result = $model->save(false);
            if($result){
                $return = ['success', '操作成功哦'];
            }else{
                $return = ['error', '操作失败哦'];
            }
        }else{
            $return = ['error', '操作失败哦'];
        }
        return $return;
    }

    /**
     * 自定义培训添加
     * @author zhaiyu
     * @return string
     */
    public function actionCreate()
    {
        $model = new Resource();
        /*获取目录的树形结构*/
        $directoryStructureData = (new ResourceClass())->getDataForWhere(['attr_type' => 1]);
        $directoryStructureList = $this->TreeList($directoryStructureData);
        return $this->render('save_custom', [
            'model' => $model,
            'directoryStructureList' => $directoryStructureList,
            'title' => '自定义培训-添加',
        ]);
    }

    /**
     * 自定义培训修改
     * @author zhaiyu
     * @param $id
     * @return string
     */
    public function actionUpdate($id)
    {
        $appYii = Yii::$app;
        $referrer = $appYii->request->referrer ?? 'index';//跳转地址
        if($id){
            $model = $this->findModel($id);
            $model->rids = explode(',', $model->rids);
            /*获取目录的树形结构*/
            $directoryStructureData = (new ResourceClass())->getDataForWhere(['attr_type' => 1]);
            $directoryStructureList = $this->TreeList($directoryStructureData);
            return $this->render('save_custom', [
                'model' => $model,
                'directoryStructureList' => $directoryStructureList,
                'title' => '自定义培训-编辑',
            ]);
        }else{
            $this->redirect($referrer);
        }
    }

    /**
     * 培训删除
     * @author zhaiyu
     * @return \yii\web\Response
     */
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
                (new Resource())->saveData(['id' => $params['selection']], ['status' => 1]);
            } elseif ('disable' == $params['type']) {
                /*禁用*/
                (new Resource())->saveData(['id' => $params['selection']], ['status' => 0]);
            } elseif ('isPub' == $params['type']) {
                /*发布*/
                (new Resource())->saveData(['id' => $params['selection']], ['publish_status' => 1, 'publish_time' => time()]);
            } elseif ('noPub' == $params['type']) {
                /*取消发布*/
                (new Resource())->saveData(['id' => $params['selection']], ['publish_status' => 0]);
            } elseif ('isRec' == $params['type']) {
                /*推荐*/
                (new Resource())->saveData(['id' => $params['selection']], ['recommend_status' => 1]);
            } elseif ('noRec' == $params['type']) {
                /*取消推荐*/
                (new Resource())->saveData(['id' => $params['selection']], ['recommend_status' => 0]);
            }
        }elseif($id){
            $this->findModel($id)->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return null|static
     */
    protected function findModel($id)
    {
        if (($model = Resource::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * 药店培训列表页
     * @author zhaiyu
     * @return string
     */
    public function actionPharmacy()
    {

        $appYii = Yii::$app;

        /*带搜索的目录树形结构*/
        $resourceClassModel = new ResourceClass();
        $directoryStructureSearch = $resourceClassModel->recursionTree(0,['attr_type' => 0]);//获取药店分类树形图
        $treeNavigateSelected = [];
        if(isset($appYii->request->queryParams['Resource']['rid'])){
            $treeNavigateSelected = $resourceClassModel->getDataForWhere(['id' => $appYii->request->queryParams['Resource']['rid']]);
        }

        /*条件查询*/
        $search = new ResourceSearch();
        $dataProvider = $search->search($appYii->request->queryParams, 0);

        /*导出数据处理*/
        $exportData = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $exportData[$key]['title'] = $val->title;
            $exportData[$key]['hour'] = $val->hour;
            $exportData[$key]['author'] = $val->author;
            $exportData[$key]['views'] = $val->views;
            $exportData[$key]['comments'] = $val->comments;
            $exportData[$key]['uid'] = $val->uid ? User::find()->where(['id' => $val->uid])->one()->username : '';
            $exportData[$key]['recommend_status'] = $appYii->params['recStatusOption'][$val->recommend_status];
            $exportData[$key]['publish_status'] =$appYii->params['pubStatusOption'][$val->publish_status];
            $exportData[$key]['status'] = $appYii->params['statusOption'][$val->status];
            $exportData[$key]['publish_time'] = $val->publish_status == 1 ? date('Y-m-d H:i:s', $val->publish_time) : '';
            $exportData[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);
        }

        /*将数据存入cookie以便导出*/
        $appYii->response->cookies->add(new Cookie([
            'name' => 'resourcePharmacyDataExportToExcel',
            'value' => json_encode($exportData),
        ]));

        return $this->render('pharmacy', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'directoryStructureSearch' => json_encode($directoryStructureSearch),
            'treeNavigateSelectedName' => $treeNavigateSelected[0]['name'] ?? '',
        ]);
    }

    /**
     * 药店培训修改
     * @author zhaiyu
     * @param $id
     * @return string
     */
    public function actionUpdate_pha($id)
    {
        $appYii = Yii::$app;
        $referrer = $appYii->request->referrer ?? 'index';//跳转地址
        if($id){
            $model = $this->findModel($id);
            $model->rids = explode(',', $model->rids);
            /*获取目录的树形结构*/
            $directoryStructureData = (new ResourceClass())->getDataForWhere(['attr_type' => 0]);
            $directoryStructureList = $this->TreeList($directoryStructureData);
            return $this->render('save_pharmacy', [
                'model' => $model,
                'directoryStructureList' => $directoryStructureList,
                'title' => '药店培训-编辑',
            ]);
        }else{
            $this->redirect($referrer);
        }
    }

    /**
     * 药店培训添加
     * @author zhaiyu
     * @return string
     */
    public function actionCreate_pha()
    {
        $model = new Resource();
        /*获取目录的树形结构*/
        $directoryStructureData = (new ResourceClass())->getDataForWhere(['attr_type' => 0]);
        $directoryStructureList = $this->TreeList($directoryStructureData);
        return $this->render('save_pharmacy', [
            'model' => $model,
            'directoryStructureList' => $directoryStructureList,
            'title' => '药店培训-添加',
        ]);
    }

    /**
     * 自定义培训保存
     * @author zhaiyu
     */
    public function actionForm_pha()
    {
        $post = Yii::$app->request->post();
        $return = $this->CommonSave($post);
        Yii::$app->getSession()->setFlash($return[0], $return[1]);
        $this->redirect('pharmacy');
    }

    /**
     * 培训导出
     * @author zhaiyu
     */
    public function actionExport(){
        $column = [
            'title'=>['column'=>'A','name'=>'资源名','width'=>20],
            'author'=>['column'=>'B','name'=>'作者','width'=>20],
            'views'=>['column'=>'C','name'=>'浏览次数','width'=>10],
            'comments'=>['column'=>'D','name'=>'评论次数','width'=>10],
            'uid'=>['column'=>'E','name'=>'创建者','width'=>20],
            'recommend_status'=>['column'=>'F','name'=>'推荐状态','width'=>10],
            'publish_status'=>['column'=>'G','name'=>'发布状态','width'=>10],
            'status'=>['column'=>'H','name'=>'状态','width'=>10],
            'publish_time'=>['column'=>'I','name'=>'发布时间','width'=>20],
            'created_at'=>['column'=>'J','name'=>'创建时间','width'=>20],
        ];
        $config = [
            'fileName' => '自定义培训数据导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $data = Yii::$app->request->cookies->getValue('resourceDataExportToExcel');
//        var_dump(json_decode($data, true));
//        var_dump($data);
//        exit;
        $excel = new ExcelController();
        $excel->Export($config, $column, json_decode($data, true));
    }

    /**
     * 药店培训导出
     * @author zhaiyu
     */
    public function actionExport_pha(){
        $column = [
            'title'=>
                ['column'=>'A','name'=>'资源名','width'=>20],
            'hour'=>
                ['column'=>'B','name'=>'时长(分钟)','width'=>20],
            'author'=>
                ['column'=>'C','name'=>'作者','width'=>20],
            'views'=>
                ['column'=>'D','name'=>'浏览次数','width'=>10],
            'comments'=>
                ['column'=>'E','name'=>'评论次数','width'=>10],
            'uid'=>
                ['column'=>'F','name'=>'创建者','width'=>20],
            'recommend_status'=>
                ['column'=>'G','name'=>'推荐状态','width'=>10],
            'publish_status'=>
                ['column'=>'H','name'=>'发布状态','width'=>10],
            'status'=>
                ['column'=>'I','name'=>'状态','width'=>10],
            'publish_time'=>
                ['column'=>'J','name'=>'发布时间','width'=>20],
            'created_at'=>
                ['column'=>'K','name'=>'创建时间','width'=>20],
        ];
        $config = [
            'fileName' => '药店培训数据导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $data = Yii::$app->request->cookies->getValue('resourcePharmacyDataExportToExcel');
        $excel = new ExcelController();
        $excel->Export($config, $column, json_decode($data, true));
    }
}

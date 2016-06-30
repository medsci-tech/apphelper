<?php

namespace backend\controllers;

use backend\models\search\Resource as ResourceSearch;
use yii\data\ActiveDataProvider;
use common\models\Resource;
use common\models\ResourceClass;
use common\models\User;
use Yii;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ResourceController extends BackendController
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    'imageUrlPrefix' => '', //图片访问路径前缀
                    'imagePathFormat' => 'upload/image/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径
                ],
            ],
            //'webupload' => WebuploaderAction::className(),
        ];
    }
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
        $query = $search->search($appYii->request->queryParams, 1);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);

        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'directoryStructureSearch' => json_encode($directoryStructureSearch),
            'treeNavigateSelectedName' => $treeNavigateSelected[0]['name'] ?? '',
        ]);
    }

    /**
     * 培训保存公共方法
     * @author zhaiyu
     * @return array
     */
    public function actionForm()
    {
        $post = Yii::$app->request->post();
        $get = Yii::$app->request->get();
        $id = $get['id'] ?? false;
        if($id){
            //有id修改
            $model = $this->findModel($id);
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][2].$id); //删除缓存
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
            $model->rids = implode(',', array_unique($model->rids));
            $result = $model->save(false);
            if ($result) {
                $return = ['code' => 200, 'msg' => '', 'data' => ''];
            } else {
                $return = ['code' => 801, 'msg' => '服务端操作失败', 'data' => ''];
            }
        }else{
            $return = ['code'=>802,'msg'=>'数据有误','data'=>''];
        }
        self::clearIndex();// 更新app首页缓存
        $this->ajaxReturn($return);
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
        self::clearIndex();// 更新app首页缓存
        /* 更新详情缓存 */
        foreach ($params['selection'] as $key => $val) {
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][2].$val); //删除缓存
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
        $query = $search->search($appYii->request->queryParams, 0);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);

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
     * 培训导出
     * @author zhaiyu
     */
    public function actionExport(){
        $appYii = Yii::$app;
        /*条件查询*/
        $search = new ResourceSearch();
        $query = $search->search($appYii->request->queryParams, 1);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
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
        $column = [
            'title'=>['column'=>'A','name'=>'资源名','width'=>30],
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
        $excel = new ExcelController();
        $excel->Export($config, $column, $exportData);
    }

    /**
     * 药店培训导出
     * @author zhaiyu
     */
    public function actionExport_pha(){
        $appYii = Yii::$app;
        $search = new ResourceSearch();
        $query = $search->search($appYii->request->queryParams, 0);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);
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
        $column = [
            'title'=>
                ['column'=>'A','name'=>'资源名','width'=>30],
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
        $excel = new ExcelController();
        $excel->Export($config, $column, $exportData);
    }
    
    /**
     * App首页缓存更新
     * @author lxh
     */
    private function clearIndex()
    {
        Yii::$app->cache->delete(Yii::$app->params['redisKey'][3]);    
    }

}

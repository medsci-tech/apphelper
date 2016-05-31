<?php

namespace backend\controllers;

use backend\models\search\Resource as ResourceSearch;
use common\models\Resource;
use common\models\ResourceClass;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ResourceController extends BackendController
{

    /**
     * 自定义培训
     * @return string
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;

        /*带搜索的目录树形结构*/
        $directoryStructureModel = new ResourceClass();
        $directoryStructureSearch = $directoryStructureModel->recursionTree(0,['attr_type' => 1]);//获取自定义分类树形图
        $treeNavigateSelected = [];
        if(isset($appYii->request->queryParams['Resource']['rid'])){
            $treeNavigateSelected = $directoryStructureModel->getDataForWhere(['id' => $appYii->request->queryParams['Resource']['rid']]);
        }

        /*条件查询*/
        $search = new ResourceSearch();
        $dataProvider = $search->search($appYii->request->queryParams);

        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'directoryStructureSearch' => json_encode($directoryStructureSearch),
            'treeNavigateSelectedName' => $treeNavigateSelected[0]['name'] ?? '',
        ]);

    }

    public function actionForm()
    {
        $post = Yii::$app->request->post();
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
        Yii::$app->getSession()->setFlash($return[0], $return[1]);
        $this->redirect('index');
    }

    public function actionCreate()
    {
        $model = new Resource();
        /*获取目录的树形结构*/
        $directoryStructureData = (new ResourceClass())->getDataForWhere();
        $directoryStructureList = $this->TreeList($directoryStructureData);
        return $this->render('update', [
            'model' => $model,
            'directoryStructureList' => $directoryStructureList,
            'title' => '添加资源',
        ]);
    }
    public function actionUpdate($id)
    {
        $appYii = Yii::$app;
        $referrer = $appYii->request->referrer ?? 'index';//跳转地址
        if($id){
            $model = $this->findModel($id);
            $model->rids = explode(',', $model->rids);
            /*获取目录的树形结构*/
            $directoryStructureData = (new ResourceClass())->getDataForWhere();
            $directoryStructureList = $this->TreeList($directoryStructureData);
            return $this->render('update', [
                'model' => $model,
                'directoryStructureList' => $directoryStructureList,
                'title' => '编辑资源',
            ]);
        }else{
            $this->redirect($referrer);
        }
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
     * @throws NotFoundHttpException
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
     * @return mixed
     */
    public function actionView($id)
    {

    }

    /**
     * 编辑初始化查询数据
     * @param $id
     */
    public function actionFind($id)
    {
        if($id){
            $result = Resource::find()->where(['id' => $id])->asArray()->one();
            if($result){
                $return = [200,'',$result];
            }else{
                $return = [801];
            }
        }else{
            $return = [802];
        }
        $this->ajaxReturn($return);
    }

    public function actionPharmacy()
    {
        echo '药店培训首页';
    }
}

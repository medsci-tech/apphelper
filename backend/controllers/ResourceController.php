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

        /*获取目录的树形结构*/
        $directoryStructureData = $directoryStructureModel->getDataForWhere();
        $tree = new TreeController($directoryStructureData, ' |- ');
        $directoryStructureTree = $tree->get_tree('id', 'name');
        $directoryStructureData = [];
        if($directoryStructureTree){
            foreach ($directoryStructureTree as $key => $val){
                $directoryStructureData[$val['id']] = $val['name'];
            }
        }
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'directoryStructureSearch' => json_encode($directoryStructureSearch),
            'treeNavigateSelectedName' => $treeNavigateSelected[0]['name'] ?? '',
            'directoryStructureData' => $directoryStructureData,
        ]);

    }

    public function actionForm()
    {

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

    public function actionPharmacy()
    {
        echo '药店培训';
    }
}

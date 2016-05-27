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

<?php

namespace backend\controllers;
use common\models\Region;
use frontend\controllers\FrontendController;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\search\Hospital as HospitalSearch;
use common\models\Hospital;
use yii\data\ActiveDataProvider;
use common\models\Upload;
use yii\web\UploadedFile;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class HospitalController extends BackendController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $uploadModel = new Upload();
        $uploadModel->file = UploadedFile::getInstance($uploadModel, 'file');
        if($uploadModel->file){
            $fileData = $uploadModel->excel(Yii::getAlias('@webroot/uploads'));
            if (200 == $fileData['code']) {
                $import = $this->actionImport($fileData['data']);
                if(200 == $import['code']){
                    echo 'success';
                }else{
                    echo $import['msg'];
                }
            }else{
                echo $fileData['msg'];
            }
        }

        $searchModel = new HospitalSearch();
        $dataProvider = $searchModel->search($appYii->request->queryParams);

        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val) {
//            var_dump($val->province); exit;
            $dataArray[$key]['id'] = $val->id;
            $dataArray[$key]['name'] = $val->name;
            $dataArray[$key]['province_id'] = $val->province_id;
            $dataArray[$key]['province'] = $val->province;
            $dataArray[$key]['city_id'] = $val->city_id;
            $dataArray[$key]['city'] = $val->city;
            $dataArray[$key]['area_id'] = $val->area_id;
            $dataArray[$key]['area'] = $val->area;
            $dataArray[$key]['address'] = $val->address;
            $dataArray[$key]['status'] = $appYii->params['statusOption'][$val->status];
        }

        /*将数据存入cache以便导出*/
        $appYii->cache->set('hospitalDataExportToExcel',json_encode($dataArray));
//        $appYii->response->cookies->add(new Cookie([
//            'name' => 'hospitalDataExportToExcel',
//            'value' => json_encode($dataArray),
//        ]));

        return $this->render('index', [
            'model' => new Hospital(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'uploadModel' => $uploadModel,
        ]);
    }

    public function actionView($id)
    {
        $params = Yii::$app->params;
        $model = $this->findModel($id);
        $model->status =  $params['statusOption'][$model->status];
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionForm()
    {
        $appYii = Yii::$app;
        if(isset($appYii->request->get()['id'])){
            $id = $appYii->request->get()['id'];
            $model = $this->findModel($id);
            if(empty($model)){
                $model = new Hospital();
            }
        }else{
            $model = new Hospital();
        }

        $model->load($appYii->request->post());
        $isValid = $model->validate();
        if ($isValid) {
            if(!isset($model->id)){
                $model->created_at = time();
            }
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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Hospital::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionModify()
    {
        $params = Yii::$app->request->post();
        if('disable' == $params['type']){
            /*禁用*/
            foreach ($params['selection'] as $key => $val){
                $model = $this->findModel($val);
                $model->status = 0;
                $model->save(false);
            }
        }else if('enable' == $params['type']){
            /*启用*/
            foreach ($params['selection'] as $key => $val){
                $model = $this->findModel($val);
                $model->status = 1;
                $model->save(false);
            }
        } elseif ('del' == $params['type']) {
            /*删除*/
            foreach ($params['selection'] as $key => $val) {
                $this->findModel($val)->delete();
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * 用户数据导出
     */
    public function actionExport(){
        $column = [
            'id'=>['column'=>'A','name'=>'编号','width'=>10],
            'name'=>['column'=>'B','name'=>'单位名称','width'=>30],
            'province_id'=>['column'=>'C','name'=>'省份编码','width'=>10],
            'province'=>['column'=>'D','name'=>'省份','width'=>10],
            'city_id'=>['column'=>'E','name'=>'城市编码','width'=>10],
            'city'=>['column'=>'F','name'=>'城市','width'=>10],
            'area_id'=>['column'=>'G','name'=>'县区编码','width'=>10],
            'area'=>['column'=>'H','name'=>'县区','width'=>10],
            'address'=>['column'=>'I','name'=>'地址','width'=>50],
            'status'=>['column'=>'J','name'=>'状态','width'=>10],
        ];
        $config = [
            'fileName' => '用户数据导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $data = Yii::$app->cache->get('hospitalDataExportToExcel');
//        var_dump(json_decode($data, true));exit;
        $excel = new ExcelController();
        $excel->Export($config, $column, json_decode($data, true));
    }

    protected function actionImport($fileName){
        $appYii = Yii::$app;

        //文件上传成功
        $column = [
            'name'=>'单位名称',
            'province_id'=>'省份编码',
            'province' =>'省份',
            'city_id'=>'城市编码',
            'city' =>'城市',
            'area_id'=>'县区编码',
            'area' =>'县区',
            'address' => '地址',
            'status'=>'状态',
        ];
//                $fileName = $fileData['data'];
        $excel = new ExcelController();
        $result = $excel->Import($fileName, $column);
        if(200 == $result['code']){
            $transaction = $appYii->db->beginTransaction(); //开启事务
            try {
//                $rank = $appYii->params['member']['rank'];
                $status = $appYii->params['statusOption'];
//                $hospital = new Hospital();
                foreach ($result['data'] as $key => $val){
                    $val['created_at'] = time();
                    $val['status'] = array_search($val['status'], $status);
                    $val['province_id'] = Region::find()->andFilterWhere(['like', 'name', $val['province_id']])->one()->id;
                    $val['city_id'] =  Region::find()->andFilterWhere(['like', 'name', $val['city_id']])->one()->id;
                    $val['area_id'] =  Region::find()->andFilterWhere(['like', 'name', $val['area_id']])->one()->id;
                    $val['province'] = Region::find()->andFilterWhere(['like', 'name', $val['province_id']])->one()->name;
                    $val['city'] =  Region::find()->andFilterWhere(['like', 'name', $val['city_id']])->one()->name;
                    $val['area'] =  Region::find()->andFilterWhere(['like', 'name', $val['area_id']])->one()->name;
                    $val['address'] = $val['address'];
                    $appYii->db->createCommand()->insert('{{%hospital}}',$val)->execute();
                }
                $transaction->commit(); // 两条sql均执行成功，则提交
                $return = ['code' => 200,'msg' => 'success'];
            } catch (\Exception $e) {
                $transaction->rollBack(); // 事务执行失败，则回滚
                $return = ['code' => 602,'msg' => '导入失败'];
            }
        }else{
            $return = ['code' => 603,'msg' => $result['msg']];
        }
        return $return;
    }

}

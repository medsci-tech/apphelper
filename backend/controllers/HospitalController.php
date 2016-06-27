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

        $searchModel = new HospitalSearch();
        $dataProvider = $searchModel->search($appYii->request->queryParams);

        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val) {
            $dataArray[$key]['name'] = $val->name;
            $dataArray[$key]['province'] = $val->province;
            $dataArray[$key]['city'] = $val->city;
            $dataArray[$key]['area'] = $val->area;
            $dataArray[$key]['address'] = $val->address;
            $dataArray[$key]['status'] = $appYii->params['statusOption'][$val->status];
        }

        /*将数据存入cache以便导出*/
        $appYii->cache->set('hospitalDataExportToExcel',json_encode($dataArray));

        return $this->render('index', [
            'model' => new Hospital(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    public function actionExport($default){
        $column = [
            'name'=>['column'=>'A','name'=>'单位名称','width'=>30],
            'province'=>['column'=>'B','name'=>'省份','width'=>10],
            'city'=>['column'=>'C','name'=>'城市','width'=>10],
            'area'=>['column'=>'D','name'=>'县区','width'=>10],
            'address'=>['column'=>'E','name'=>'地址','width'=>50],
            'status'=>['column'=>'F','name'=>'状态','width'=>10],
        ];
        $config = [
            'fileName' => '单位导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        if($default){
            $data = [];
            $config['fileName'] = '单位导入模板';
        }else{
            $data = json_decode(Yii::$app->cache->get('hospitalDataExportToExcel'),true);
        }

        $excel = new ExcelController();
        $excel->Export($config, $column, $data);
    }

    protected function actionImport($fileName){
        $appYii = Yii::$app;

        //文件上传成功
        $column = [
            'name'=>'单位名称',
            'province' =>'省份',
            'city' =>'城市',
            'area' =>'县区',
            'address' => '地址',
            'status'=>'状态',
        ];
        $excel = new ExcelController();
        $result = $excel->Import($fileName, $column);
        if(200 == $result['code']){
            $transaction = $appYii->db->beginTransaction(); //开启事务
            try {
                $status = $appYii->params['statusOption'];
                foreach ($result['data'] as $key => $val){
                    $province = Region::find()->andFilterWhere(['like', 'name', $val['province']])->one();
                    $city = Region::find()->andFilterWhere(['like', 'name', $val['city']])
                            ->andFilterWhere(['parentCode' => $province->code ?? 0])
                            ->one();
                    $area = Region::find()->andFilterWhere(['like', 'name', $val['area']])
                            ->andFilterWhere(['parentCode' => $city->code ?? 0])
                            ->one();

                    $val['created_at'] = time();
                    $val['status'] = array_search($val['status'], $status);
                    $val['province_id'] = $province->code ?? 0;
                    $val['city_id'] =  $city->code ?? 0;
                    $val['area_id'] =  $area->code ?? 0;
                    $appYii->db->createCommand()->insert('{{%hospital}}',$val)->execute();
                }
                $transaction->commit(); // 两条sql均执行成功，则提交
                $return = ['code' => 200,'msg' => 'success'];
            } catch (\Exception $e) {
                $transaction->rollBack(); // 事务执行失败，则回滚
                $return = ['code' => 602,'msg' => '导入数据库失败'];
            }
        }else{
            $return = ['code' => 603,'msg' => $result['msg']];
        }
        return $return;
    }

    /**
     * 用户导入上传Excel表
     */
    public function actionUpexcel(){
        $post = Yii::$app->request->post();
        if ($post['excel']) {
            $import = $this->actionImport($post['excel']);
            if(200 == $import['code']){
                $return = ['code'=>200,'msg'=>'success','data'=>''];
            }else{
                $return = ['code'=>801,'msg'=>$import['msg'],'data'=>''];
            }
        }else{
            $return = ['code'=>802,'msg'=>'文件上传失败','data'=>''];
        }
        $this->ajaxReturn($return);
    }

}

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
        $query = $searchModel->search($appYii->request->queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'name' => SORT_ASC,
                ]
            ],
        ]);
        
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
            if ($result) {
                $return = ['code' => 200, 'msg' => '', 'data' => ''];
            } else {
                $return = ['code' => 801, 'msg' => '服务端操作失败', 'data' => ''];
            }
        }else{
            $attributeLabels = $model->attributeLabels();
            $getError = '数据验证错误';
            foreach ($model->errors as $key => $val){
                $getError = $attributeLabels[$key] . $val[0];
                break;
            }
            $return = ['code' => 802, 'msg' => $getError, 'data' => ''];
        }
        $this->ajaxReturn($return);
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
        $data = [];
        if($default){
            $config['fileName'] = '单位导入模板';
        }else{
            $appYii = Yii::$app;
            $searchModel = new HospitalSearch();
            $query = $searchModel->search($appYii->request->queryParams);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 0,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                        'name' => SORT_ASC,
                    ]
                ],
            ]);
            foreach ($dataProvider->getModels() as $key => $val) {
                $data[$key]['name'] = $val->name;
                $data[$key]['province'] = $val->province;
                $data[$key]['city'] = $val->city;
                $data[$key]['area'] = $val->area;
                $data[$key]['address'] = $val->address;
                $data[$key]['status'] = $appYii->params['statusOption'][$val->status];
            }
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

    /**
     * 根据地区筛选医院
     */
    public function actionGetHospitalByRegion(){
        $post = Yii::$app->request->post();
        $where = [];
        $province_id = $post['province_id'] ?? '420000';
        $city_id = $post['city_id'] ?? '420100';
        $area_id = $post['area_id'] ?? '420103';
        if($province_id){
            $where['province_id'] = $province_id;
            if($city_id){
                $where['city_id'] = $city_id;
                if($area_id){
                    $where['area_id'] = $area_id;
                }
            }
        }
        $hospital = (new Hospital())->getDataForWhere($where);
        $data = [];
        foreach ($hospital as $key => $val){
            $data[$key] = [
                'id' => $val['id'],
                'name' => $val['name'],
            ];
        }
        $return = ['code'=>200,'msg'=>'','data'=>$data];
        $this->ajaxReturn($return);
    }

}

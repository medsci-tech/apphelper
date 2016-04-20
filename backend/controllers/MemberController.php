<?php

namespace backend\controllers;

use common\models\Hospital;
use common\models\Member;
use common\models\Region;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class MemberController extends BackendController
{

    /**
     * Lists all Article models.
     *'real_name',
    'username',
    'email',
     * @return mixed
     */
    public function actionIndex()
    {
        $searchMember = new \backend\models\search\Member();
        $dataProvider = $searchMember->search(Yii::$app->request->queryParams);
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $dataArray[$key]['real_name'] = $val->real_name;
            $dataArray[$key]['username'] = $val->username;
            $dataArray[$key]['email'] = $val->email;
            $dataArray[$key]['hospital_id'] = Hospital::findOne($val->hospital_id)->name;
            $dataArray[$key]['rank_id'] = Yii::$app->params['member']['rank'][$val->rank_id];
            $dataArray[$key]['province_id'] =  Region::findOne($val->province_id)->name;
            $dataArray[$key]['city_id'] =  Region::findOne($val->city_id)->name;
            $dataArray[$key]['area_id'] =  Region::findOne($val->area_id)->name;
            $dataArray[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);
        }
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'memberDataExportToExcel',
            'value' => json_encode($dataArray),
        ]));

        $memberRank = Yii::$app->params['member'];
        return $this->render('index', [
            'searchModel' => $searchMember,
            'dataProvider' => $dataProvider,
            'memberRank' => $memberRank,
        ]);
    }

    /**
     * Displays a single Article model.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $member = $this->findModel($id);
        $member->hospital_id = Hospital::findOne($member->hospital_id)->name;
        $member->rank_id = Yii::$app->params['member']['rank'][$member->rank_id];
        $member->province_id =  Region::findOne($member->province_id)->name;
        $member->city_id =  Region::findOne($member->city_id)->name;
        $member->area_id =  Region::findOne($member->area_id)->name;
        return $this->render('view', [
            'model' => $member,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member();
        if ($model->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
            if ($isValid) {
                $model->created_at = time();
                $res = $model->save(false);
                if($res && $model->signup()){
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect(['create']);
                }
            }else{
                return $this->redirect(['create']);
            }
        }else{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
//            var_dump($model->province_id);exit;
            if ($isValid) {
                $model->updated_at = time();
                $res = $model->save(false);
                if($res){
                    return $this->redirect(['index']);
                }else{
                    return $this->redirect(['update?id=' . $id]);
                }
            }else{
                return $this->redirect(['update?id=' . $id]);
            }
        }else{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionImport(){
        $column = [
            'title'=>'标题',
            'uid'=>'UID',
            'url'=>'地址',
        ];
        $fileName = 'E:\work\02simple.xls';
        $excel = new ExcelController();
        $data = $excel->Import($fileName, $column);
        if($data){
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction(); //开启事务
            try {
                $time = time();
                foreach ($data as $key => $val){
                    $val['created_at'] = $time;
                    $db->createCommand()->insert('{{%guide}}',$val)->execute();
                }
                $transaction->commit(); // 两条sql均执行成功，则提交
            } catch (\Exception $e) {
                $transaction->rollBack(); // 事务执行失败，则回滚
            }
        }else{
            echo 'error';
        }
    }

    /**
     * 用户数据导出
     */
    public function actionExport(){
        $column = [
            'real_name'=>['column'=>'A','name'=>'姓名','width'=>20],
            'username'=>['column'=>'B','name'=>'手机号','width'=>20],
            'email'=>['column'=>'C','name'=>'邮箱','width'=>30],
            'hospital_id'=>['column'=>'D','name'=>'医院','width'=>20],
            'rank_id'=>['column'=>'E','name'=>'职称','width'=>10],
            'province_id'=>['column'=>'F','name'=>'省份','width'=>10],
            'city_id'=>['column'=>'G','name'=>'城市','width'=>10],
            'area_id'=>['column'=>'H','name'=>'县区','width'=>10],
            'created_at'=>['column'=>'I','name'=>'注册时间','width'=>20],
        ];
        $config = [
            'fileName' => '用户数据导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $data = Yii::$app->request->cookies->getValue('memberDataExportToExcel');
        $excel = new ExcelController();
        $excel->Export($config, $column, json_decode($data, true));
    }



}

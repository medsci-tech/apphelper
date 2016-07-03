<?php

namespace backend\controllers;

use common\models\Hospital;
use common\models\Member;
use common\models\Region;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class MemberController extends BackendController
{

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $searchMember = new \backend\models\search\Member();
        $query = $searchMember->search($appYii->request->queryParams);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $this->render('index', [
            'searchModel' => $searchMember,
            'dataProvider' => $dataProvider,
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
        $member->hospital_id = $member->hospital_id ? Hospital::findOne($member->hospital_id)->name : '';
        $member->rank_id = Yii::$app->params['member']['rank'][$member->rank_id];
        $member->status =  Yii::$app->params['statusOption'][$member->status];
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
        return $this->render('create', [
            'model' => $model,
        ]);
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
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionForm(){
        $get = Yii::$app->request->get();
        $post = Yii::$app->request->post();
        if(isset($get['id'])){
            //有id修改
            $id = $get['id'];
            $model = $this->findModel($id);
            if(empty($model)){
                $model = new Member();
            }
        }else{
            //无id添加
            $model = new Member();
        }
        $model->load($post);
        $isValid = $model->validate();

        if ($isValid) {
            $checkUsername = $model->checkUsernameExist($model->username, $model->id);
            if(false == $checkUsername){
                if (!isset($model->id)) {
                    if(isset($model->password)){
                        $password = $model->password;
                    }else{
                        $password = Yii::$app->params['member']['defaultPwd'];
                    }
                    $model->setPassword($password);
                    $model->generateAuthKey();
                    $model->created_at = time();
                } else {
                    $model->updated_at = time();
                }
                $result = $model->save(false);
                if ($result) {
                    $return = ['code' => 200, 'msg' => '', 'data' => ''];
                } else {
                    $return = ['code' => 801, 'msg' => '服务端操作失败', 'data' => ''];
                }
            }else{
                $return = ['code' => 803, 'msg' => '手机号已存在', 'data' => ''];
            }
        }else{
            $return = ['code'=>802,'msg'=>'数据有误','data'=>''];
        }
        $this->ajaxReturn($return);
    }

    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        if(isset($params['selection'])) {
            if ('disable' == $params['type']) {
                /*禁用*/
                foreach ($params['selection'] as $key => $val) {
                    $member = $this->findModel($val);
                    $member->status = 0;
                    $member->save(false);
                }
            } elseif ('enable' == $params['type']) {
                /*启用*/
                foreach ($params['selection'] as $key => $val) {
                    $member = $this->findModel($val);
                    $member->status = 1;
                    $member->save(false);
                }
            } elseif ('del' == $params['type']) {
                /*删除*/
                foreach ($params['selection'] as $key => $val) {
                    $this->findModel($val)->delete();
                }
            }
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
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function actionImport($fileName){
        $appYii = Yii::$app;

         //文件上传成功
        $column = [
            'real_name'=>'姓名',
            'nickname'=>'昵称',
            'sex'=>'性别',
            'username'=>'手机号',
            'email'=>'邮箱',
            'hospital_id'=>'医院',
            'rank_id'=>'职称',
            'province'=>'省份',
            'city'=>'城市',
            'area'=>'县区',
            'status'=>'状态',
        ];
//                $fileName = $fileData['data'];
        $excel = new ExcelController();
        $result = $excel->Import($fileName, $column);
        if(200 == $result['code']){
            $transaction = $appYii->db->beginTransaction(); //开启事务
            try {
                $rank = $appYii->params['member']['rank'];
                $status = $appYii->params['statusOption'];
                $user = new User();
                foreach ($result['data'] as $key => $val){
                    $val['updated_at'] = time();
                    $val['created_at'] = time();
                    $val['rank_id'] = array_search($val['rank_id'], $rank);
                    $val['status'] = array_search($val['status'], $status);
                    $val['hospital_id'] = Hospital::find()->andFilterWhere(['like', 'name', $val['hospital_id']])->one()->id;
                    $val['province_id'] = Region::find()->andFilterWhere(['like', 'name', $val['province']])->one()->id;
                    $val['city_id'] =  Region::find()->andFilterWhere(['like', 'name', $val['city']])->one()->id;
                    $val['area_id'] =  Region::find()->andFilterWhere(['like', 'name', $val['area']])->one()->id;
                    $user->setPassword($appYii->params['member']['defaultPwd']);
                    $user->generateAuthKey();
                    $val['password_hash'] =$user->password_hash;
                    $val['auth_key'] =$user->auth_key;
                    $appYii->db->createCommand()->insert('{{%member}}',$val)->execute();
                }
                $transaction->commit(); // 两条sql均执行成功，则提交
                $return = ['code' => 200,'msg' => 'success'];
            } catch (\Exception $e) {
                $transaction->rollBack(); // 事务执行失败，则回滚
                $return = ['code' => 602,'msg' => $e->errorInfo[2]];
            }
        }else{
            $return = ['code' => 603,'msg' => $result['msg']];
        }
        return $return;
    }

    /**
     * 用户数据导出
     * @param $default
     */
    public function actionExport($default){
        $column = [
            'real_name'=>['column'=>'A','name'=>'姓名','width'=>20],
            'sex'=>['column'=>'B','name'=>'性别','width'=>10],
            'username'=>['column'=>'C','name'=>'手机号','width'=>20],
            'email'=>['column'=>'D','name'=>'邮箱','width'=>30],
            'hospital_id'=>['column'=>'E','name'=>'医院','width'=>20],
            'rank_id'=>['column'=>'F','name'=>'职称','width'=>10],
            'province'=>['column'=>'G','name'=>'省份','width'=>10],
            'city'=>['column'=>'H','name'=>'城市','width'=>10],
            'area'=>['column'=>'I','name'=>'县区','width'=>10],
            'status'=>['column'=>'J','name'=>'状态','width'=>10],
            'created_at'=>['column'=>'K','name'=>'注册时间','width'=>20],
        ];
        $config = [
            'fileName' => '用户导出-' . date('YmdHis'),
            'columnHeight' => '20',
            'contentHeight' => '20',
            'fontSize' => '12',
        ];
        $data = [];
        if($default){
            $config['fileName'] = '用户导入模板';
            unset($column['created_at']);
        }else{
            $appYii = Yii::$app;
            $searchMember = new \backend\models\search\Member();
            $query = $searchMember->search($appYii->request->queryParams);
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 0,
                ],
            ]);
            foreach ($dataProvider->getModels() as $key => $val){
                $data[$key]['real_name'] = $val->real_name;
                $data[$key]['sex'] = $val->sex;
                $data[$key]['username'] = $val->username;
                $data[$key]['email'] = $val->email;
                $data[$key]['hospital_id'] =$val->hospital_id ? Hospital::findOne($val->hospital_id)->name : '';
                $data[$key]['rank_id'] = $appYii->params['member']['rank'][$val->rank_id];
                $data[$key]['province'] =  $val->province;
                $data[$key]['city'] =  $val->city;
                $data[$key]['area'] =  $val->area;
                $data[$key]['status'] = $appYii->params['statusOption'][$val->status];
                $data[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);
            }
        }
//        var_dump($data);
        $excel = new ExcelController();
        $excel->Export($config, $column, $data);
    }

    /**
     * 用户导入上传Excel表
     */
    public function actionUpexcel(){
        $post = Yii::$app->request->post();
//        var_dump($post);exit;
        if ($post['excel']) {
            $import = $this->actionImport($post['excel']);
//            var_dump($import);exit;
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

<?php

namespace backend\controllers;

use common\models\Hospital;
use common\models\Member;
use common\models\Region;
use common\models\User;
use Yii;
use yii\base\Object;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use common\models\Upload;
use yii\web\UploadedFile;

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
        $uploadModel = new Upload();
        $uploadModel->file = UploadedFile::getInstance($uploadModel, 'file');
        if('' != $uploadModel->file){
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
        $searchMember = new \backend\models\search\Member();
        $dataProvider = $searchMember->search($appYii->request->post());
        $dataArray = [];
        foreach ($dataProvider->getModels() as $key => $val){
            $dataArray[$key]['real_name'] = $val->real_name;
            $dataArray[$key]['nickname'] = $val->nickname;
            $dataArray[$key]['username'] = $val->username;
            $dataArray[$key]['email'] = $val->email;
            $dataArray[$key]['hospital_id'] =$val->hospital_id ? Hospital::findOne($val->hospital_id)->name : '';
            $dataArray[$key]['rank_id'] = $val->rank_id ? $appYii->params['member']['rank'][$val->rank_id] : '';
            $dataArray[$key]['province_id'] =  $val->province_id ? Region::findOne($val->province_id)->name : '';
            $dataArray[$key]['city_id'] =  $val->city_id ? Region::findOne($val->city_id)->name : '';
            $dataArray[$key]['area_id'] =  $val->area_id ? Region::findOne($val->area_id)->name : '';
            $dataArray[$key]['status'] = $appYii->params['statusOption'][$val->status];
            $dataArray[$key]['created_at'] = date('Y-m-d H:i:s', $val->created_at);
        }
        $appYii->response->cookies->add(new Cookie([
            'name' => 'memberDataExportToExcel',
            'value' => json_encode($dataArray),
        ]));

        return $this->render('index', [
            'searchModel' => $searchMember,
            'dataProvider' => $dataProvider,
            'params' => $appYii->params,
            'uploadModel' => $uploadModel,
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
            'username'=>'手机号',
            'email'=>'邮箱',
            'hospital_id'=>'医院',
            'rank_id'=>'职称',
            'province_id'=>'省份',
            'city_id'=>'城市',
            'area_id'=>'县区',
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
                    $val['province_id'] = Region::find()->andFilterWhere(['like', 'name', $val['province_id']])->one()->id;
                    $val['city_id'] =  Region::find()->andFilterWhere(['like', 'name', $val['city_id']])->one()->id;
                    $val['area_id'] =  Region::find()->andFilterWhere(['like', 'name', $val['area_id']])->one()->id;
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
                $return = ['code' => 602,'msg' => '导入失败'];
            }
        }else{
            $return = ['code' => 603,'msg' => $result['msg']];
        }
        return $return;
    }

    /**
     * 用户数据导出
     */
    public function actionExport(){
        $column = [
            'real_name'=>['column'=>'A','name'=>'姓名','width'=>20],
            'nickname'=>['column'=>'B','name'=>'昵称','width'=>20],
            'username'=>['column'=>'C','name'=>'手机号','width'=>20],
            'email'=>['column'=>'D','name'=>'邮箱','width'=>30],
            'hospital_id'=>['column'=>'E','name'=>'医院','width'=>20],
            'rank_id'=>['column'=>'F','name'=>'职称','width'=>10],
            'province_id'=>['column'=>'G','name'=>'省份','width'=>10],
            'city_id'=>['column'=>'H','name'=>'城市','width'=>10],
            'area_id'=>['column'=>'I','name'=>'县区','width'=>10],
            'created_at'=>['column'=>'J','name'=>'注册时间','width'=>20],
            'status'=>['column'=>'K','name'=>'状态','width'=>10],
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



    public function actionTest(){
        $targetFolder = '/uploads'; // Relative to the root

        $verifyToken = md5('unique_salt' . $_POST['timestamp']);

        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $tempFile = $_FILES['Filedata']['tmp_name'];
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
            $targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['Filedata']['name']);

            if (in_array($fileParts['extension'],$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);
                echo '1';
            } else {
                echo 'Invalid file type.';
            }
        }
    }
}

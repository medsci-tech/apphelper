<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/28
 * Time: 14:04
 */

namespace backend\controllers;

use frontend\controllers\FrontendController;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\search\User as UserSearch;
use common\models\User;
use yii\data\ActiveDataProvider;
use common\models\Upload;
use yii\web\UploadedFile;


class BackendUserController extends BackendController {

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

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($appYii->request->queryParams);

        return $this->render('index', [
            'model' => new User(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        $params = Yii::$app->params;
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionForm()
    {
        $appYii = Yii::$app;
        if(isset($appYii->request->get()['id'])){
            $id = $appYii->request->get()['id'];
            $model = $this->findModel($id);
            if(empty($model)){
                $model = new User();
            }
        }else{
            $model = new User();
        }
        $model->load($appYii->request->post());
        $isValid = $model->validate();
        if ($isValid) {
            if(!isset($model->id)){
                if(isset($model->password)){
                    $password = $model->password;
                }else{
                    $password = Yii::$app->params['member']['defaultPwd'];
                }
                $model->setPassword($password);
                $model->generateAuthKey();
                $model->created_at = time();
            }
            $result = $model->save(false);
            if ($result) {
                $return = ['code' => 200, 'msg' => '', 'data' => ''];
            } else {
                $return = ['code' => 801, 'msg' => '服务端操作失败', 'data' => ''];
            }
        }else{
            $return = ['code'=>802,'msg'=>'数据有误','data'=>''];
        }
        $this->ajaxReturn($return);
    }
}
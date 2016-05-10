<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;
use api\common\models\LoginForm;
use yii\web\Response;
use api\common\models\member;
class SiteController extends CommonController
{
    public $modelClass = 'api\common\models\Member';//Yii::$app->getRequest()->getBodyParams()['newsItem'];

    protected function verbs(){
        return [
            'sign'=>['POST'],
            'send'=>['POST'],
            'login'=>['POST'],
            'forget'=>['POST'],
        ];
    }

    /**
     * 用户注册
     * @author by lxhui
     * @version [2010-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionSign()
    {
        $model = new $this->modelClass(['scenario' => 'register']);
        $model->load($this->params, '');
        if (!$model->signup()) {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
            return $result;
        }
        else
            $data=['uid'=>$model->id,'username'=> $model->username,'access_token'=>$model->access_token];

        $result = ['code' => 200,'message'=>'注册成功!','data'=>$data];
        return $result;
    }

    /**
     * 用户登录
     * @author by lxhui
     * @version [2010-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionLogin()
    {
        $model = new $this->modelClass(['scenario' => 'login']);
        $model->load($this->params, '');
        if(!$response = $model->login())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'登录成功','data'=>['uid'=>$response->id,'access_token'=>$response->access_token]];
        return $result;
    }
    public function actionLoginbk()
    {
        $model = new LoginForm();
        $res = ['LoginForm' =>$this->params];
        if ($model->load($res))
        {
            if($model->login())
            {
                $result = ['code' => 200,'message'=>'登录成功'];
            }
            else
                $result = ['code' => -1,'message'=>'登录失败'];
        }
        return $result;

    }
    /**
     * 设置密码
     * @author by lxhui
     * @version [2010-04-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionPassword()
    {
        $model = new $this->modelClass(['scenario' => 'setPassword']);
        $model->load($this->params, '');
        if(!$response = $model->changePassword())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>['uid'=>$response->id,'access_token'=>$response->access_token]];
        return $result;
    }
    /**
     * 发送验证码
     * @author by lxhui
     * @version [2010-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionSend()
    {
        $username = $this->params['username'];
        return $this->sendCode($username);
    }

}
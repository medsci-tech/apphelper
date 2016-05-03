<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;
use api\common\models\LoginForm;
use yii\web\Response;
use api\common\models\member;use Yii;
class SiteController extends CommonController
{
    public $modelClass = 'api\common\models\Member';//Yii::$app->getRequest()->getBodyParams()['newsItem'];

    protected function verbs(){
        return [
            'sign'=>['POST'],
            'send'=>['POST'],
            'login'=>['POST'],
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
            $result = ['code' => '200','message'=>$message,'data'=>[]];
            return $result;
        }
        else
            $data=['uid'=>(string)$model->id,'username'=> $model->username,'access_token'=>$model->access_token];

        $result = ['code' => '200','message'=>'注册成功!','data'=>$data];
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
        if ($result = $model->login()) {
        } else {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => '200','message'=>$message,'data'=>[]];
        }
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
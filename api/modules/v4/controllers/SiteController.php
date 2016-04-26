<?php
namespace api\modules\v4\controllers;
use Yii;
use yii\rest\Controller;
use yii\web\Response;
use common\models\member;
use common\components\MessageSender;
class SiteController extends Controller
{
    public $modelClass = 'api\modules\v4\models\article';//Yii::$app->getRequest()->getBodyParams()['newsItem'];
    public $params;
    public $apiParams;
    public function behaviors()
    {
        $params = Yii::$app->getRequest()->getBodyParams()['apiParams'];
        $this->params = json_decode($params,true);
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;

    }
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
    protected function verbs(){
        return [
            'sign'=>['POST'],
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
        //Yii::$app->cache->set('18987890909','3467',3);
        //echo(Yii::$app->cache->get('18987890909'));
        //exit;
        $apiParams = $this->apiParams;
        $code = '-1';
        $username = $apiParams['username'];
        $password = $apiParams['password'];
        $verycode = $apiParams['verycode'];
        if(strlen($username) !=11)
            $message = '请输入有效手机号!';
        if(strlen($password) < 6)
            $message = '密码长度不能少于6位!!';
        /* 检查验证码有效性 */



            $result = ['code' => '200','message'=>'注册成功','data'=>['uid'=>101,'nickname'=>'mary01','access_token' => 'absgfjfj#$48667JUY65']];
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
        $apiParams = $_POST['apiParams'];

        // $apiParams = json_encode(['uid'=>100,'nickname'=>'mary','access_token' => 'absgfjfj#$48667JUY65']); //{"uid":100,"username":"mary","access_token":"absgfjfj#$48667JUY65"}
        $apiParams = json_decode($apiParams,true);

        $code = '-1';
        if(!$apiParams['username'] || !$apiParams['password'])
        {
            $result = ['code' => $code,'message'=>'用户密码不能为空','data'=>[]];
        }
        else{
            $result = ['code' => '200','message'=>'登录成功','data'=>['uid'=>100,'nickname'=>'mary','access_token' => 'absgfjfj#$48667JUY65']];
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
    public function actionSendCode()
    {
        $apiParams = $this->params;;
        $code = '-1';
        $username = $apiParams['username'];
        if(Yii::$app->cache->get($username))
        {
            $message = '验证码已经成功发出!';
            $result = ['code' => '200','message'=>$message,'data'=>['verycode' => Yii::$app->cache->get($username)]];
            return $result;
        }

        if(strlen($username) !=11 || !is_numeric($username))
            $message = '请输入有效手机号!';
        else
        {
            $verycode = MessageSender::generateMessageVerify();
            $flag = MessageSender::sendMessageVerify($username, $verycode); // 发送验证码
            if($flag)
                Yii::$app->cache->set($username,$verycode,60);// 缓存60s有效
            $message = '验证码已经成功发出!';
        }

        if(!$apiParams['username'] || !$apiParams['password'])
        {
            $result = ['code' => $code,'message'=>'用户密码不能为空','data'=>[]];
        }
        else{
            $result = ['code' => '200','message'=>'登录成功','data'=>['uid'=>100,'nickname'=>'mary','access_token' => 'absgfjfj#$48667JUY65']];
        }
        return $result;
    }
}
<?php
/* api 游客通用控制器 */
namespace api\common\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\auth\HttpBasicAuth;
use common\components\MessageSender;
class CommonController extends ActiveController
{
    public $params;
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


    /**
     * 发送验证码
     * @author by lxhui
     * @param username
     * @version [2010-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function sendCode($username=null)
    {
        if(!$username) return false;
        if(strlen($username) !=11 || !is_numeric($username))
        {
            $message = '请输入有效手机号!';
            $result = ['code' => '200','message'=>$message,'data'=>[]];
            return $result;
        }
        else
        {
            if(Yii::$app->cache->get($username))
            {
                $message = '验证码已经成功发出!';
                $result = ['code' => '200','message'=>$message,'data'=>['verycode' => Yii::$app->cache->get($username)]];
                return $result;
            }
            $verycode = MessageSender::generateMessageVerify();
            //$flag = MessageSender::sendMessageVerify($username, $verycode); // 发送验证码
            $flag=true;
            if($flag)
            {
                Yii::$app->cache->set($username,$verycode,60);// 缓存60s有效
                $message = '验证码已经成功发出!';
                $result = ['code' => '200','message'=>$message,'data'=>['verycode' => $verycode]];
            }
            else
                $result = ['code' => '200','message'=>'发送失败!请稍后再试!','data'=>[]];

            return $result;
        }
    }


}
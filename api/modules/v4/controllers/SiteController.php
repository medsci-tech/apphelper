<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;
use yii\web\Response;
use api\common\models\member;
use Yii;
use crazyfd\qiniu\Qiniu;
class SiteController extends CommonController
{
    public $modelClass = 'api\common\models\Member';//Yii::$app->getRequest()->getBodyParams()['newsItem'];

    protected function verbs(){
        return [
            'sign'=>['POST'],
            'send'=>['POST'],
            'login'=>['POST'],
            'forget'=>['POST'],
            'info'=>['POST'],
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
        {
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][0].$model->id); // 清除历史缓存
            Yii::$app->cache->set(Yii::$app->params['redisKey'][0].$model->id,json_encode(['uid'=>$model->id,'access_token' => $model->access_token]),2592000);
            $data=['uid'=>$model->id,'username'=> $model->username,'access_token'=>$model->access_token];
        }


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
        {
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][0].$response->id); // 清除历史缓存
            Yii::$app->cache->set(Yii::$app->params['redisKey'][0].$response->id,json_encode(['uid'=>$response->id,'access_token' => $response->access_token,'province' => $response->province]),2592000);
            $result = ['code' => 200,'message'=>'登录成功','data'=>['uid'=>$response->id,'nickname'=>$response->oldAttributes['nickname'],'avatar'=>$response->oldAttributes['avatar'],'rank_name'=>yii::$app->params['member']['rank'][$response->rank_id],'access_token'=>$response->access_token,'isComplete' =>$response->province ? true:false ]];
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
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
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

    // 临时返回token ,生成环境删除
    public function actionView()
    {
        $result = Member::find()->where(['username' => $this->params])->asArray()->one();
        if($result)
            $result = ['code' => 200,'message'=>'用户信息','data'=>['username'=>$this->params['username'],'access_token'=>$result['access_token']]];
        else
            $result = ['code' => -1,'message'=>'获取失败!','data'=>null];
        return $result;

    }
    /**
     * 功能介绍
     * @author by lxhui
     * @version [2010-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionInfo()
    {
        $data = [
            ['msg'=>'本次升级改进了很多UI']
        ];
        $result = ['code' => 200,'message' =>'功能介绍','data' =>$data];
        return $result;
    }
}
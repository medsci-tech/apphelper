<?php
namespace api\modules\v4\controllers;
use api\common\controllers\CommonController;;
use yii\web\Response;
use api\common\models\member;use Yii;
class SiteController extends CommonController
{
    public $modelClass = 'api\common\models\Member';//Yii::$app->getRequest()->getBodyParams()['newsItem'];

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
        $model = new $this->modelClass(['scenario' => 'register']);
        $model->load($this->params, '');
        if (!$model->signup()) {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => '200','message'=>$message,'data'=>[]];
            return $result;
        }
        else
            print_r($model->id);exit;

        exit;
        $result =$model->signup('15927086090','123456');print_r($result);exit;

            //$result = ['code' => '200','message'=>'注册成功','data'=>['uid'=>101,'nickname'=>'mary01','access_token' => 'absgfjfj#$48667JUY65']];
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


}
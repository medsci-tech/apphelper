<?php
namespace api\common\controllers;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;
class Controller extends ActiveController
{
    public $params;
    public $uid;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $params = Yii::$app->getRequest()->getBodyParams();
        $this->params  = $params;
        $this->uid = $this->params['uid'];
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $this->checkAccess($action=null, $model = null, $params = []);
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
     * Checks the privilege of the current user. 检查当前用户的权限
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a ForbiddenHttpException should be thrown.
     * 本方法应被覆盖来检查当前用户是否有权限执行指定的操作访问指定的数据模型
     * 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     *
     * @param string $action the ID of the action to be executed
     * @param \yii\base\Model $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        $uid = $this->uid ?? 0;
        $headers = Yii::$app->request->headers;
        $access_token = $headers->get('access-token');
        if(!$uid || !$access_token)
        {    
            $result = ['code' => -1,'message'=>'无效的uid和tocken访问验证!','data'=>null];
            exit(json_encode($result));
        }      
        $data = ['uid'=>$uid,'access_token' => $access_token];
        $mem = json_decode(Yii::$app->cache->get(Yii::$app->params['redisKey'][0].$uid),true);
        if($mem['uid'] && $mem['access_token'])
        {
            /*  验证token 是否过期 */
            if($access_token!=$mem['access_token'])
            {
                $result = ['code' => 0,'message'=>'token已过期!请重新登录!','data'=>null];
                exit(json_encode($result));
            }
            $res = array_diff_assoc($mem,$data);    
            if($res)  // 授权认证失败
            {
                $result = ['code' => -1,'message'=>'无效的tocken访问验证!','data'=>null];
                exit(json_encode($result));
            }
            else
                return;
        }
        else
        {
            $model= \api\common\models\Member::findIdentityByAccessToken($access_token);
            if($model->id!=$uid)
            {
                $result = ['code' => -1,'message'=>'tocken验证失败!','data'=>null];
                exit(json_encode($result));
            }
            else  
                Yii::$app->cache->set(Yii::$app->params['redisKey'][0].$uid,json_encode($data),2592000);
  
        }
    }
}
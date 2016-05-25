<?php
namespace api\common\controllers;
use yii\rest\ActiveController;
use yii\web\Response;
use Yii;
use api\common\models\{Member,Resource,ResourceClass};
use yii\helpers\ArrayHelper;
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
                $result = ['code' => 0,'message'=>'tocken已过期!请重新登录!','data'=>null];
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
            $model= Member::findIdentityByAccessToken($access_token);
            if($model->id!=$uid)
            {
                $result = ['code' => -1,'message'=>'tocken验证失败!','data'=>null];
                exit(json_encode($result));
            }
            else  
                Yii::$app->cache->set(Yii::$app->params['redisKey'][0].$uid,json_encode($data),2592000);
  
        }
    }
     /**
     * 获取资源所属关联是属性数据
     * author lxhui
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a ForbiddenHttpException should be thrown.
     * 本方法应被覆盖来检查当前用户是否有权限执行指定的操作访问指定的数据模型
     * 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    protected function getData($results=[])
    {
        $rids = ArrayHelper::getColumn($results, 'rid'); // 关联资源id 
        $rids_str = implode(',', $rids);   
        // 用原生 SQL 语句检索(yii2 ORM不支持field排序)
        $sql = "SELECT id,rid,title,imgurl,views FROM ".Resource::tableName()." where id in($rids_str) order by field(id,$rids_str)";
        $data = Resource::findBySql($sql)->asArray()->all();   

        $rids_2 = ArrayHelper::getColumn($data, 'rid'); // 关联资源分类id  
        $rids_str2 = implode(',', $rids_2);   
        $sql = "SELECT id,parent FROM ".ResourceClass::tableName()." where id in($rids_str2) order by field(id,$rids_str2)";
        $resource_class = ResourceClass::findBySql($sql)->asArray()->all();         
        $resource_class = ArrayHelper::map($resource_class, 'id', 'parent');

        /* 组合信息列表 */
        $count= count($data); 
        for($i=0;$i<$count;$i++)
        {  
            $data[$i]['labelName']='参与人数';
            $data[$i]['labelValue']=$data[$i]['views'];
            $data[$i]['classname']=constant("CLASSNAME")[$resource_class[$data[$i]['rid']]];
            unset($data[$i]['rid'],$data[$i]['views']);
        }  
       return $data;
    }

}
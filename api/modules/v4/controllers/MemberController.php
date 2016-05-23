<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use common\models\Hospital;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;
use yii\base\InvalidConfigException;
use api\common\models\member;
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
class MemberController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Member';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;

    }

    protected function verbs(){
        return [
            'index'=>['GET','POST'],
            'nickname'=>['POST'],
            'username'=>['POST'],
            'realname'=>['POST'],
        ];
    }
    /**
     * 个人设置首页
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $model = new $this->modelClass();
        $data = $model::find()->select('avatar,nickname,username,real_name,sex,province,city,area,hospital_id,rank_id')->where(['id'=>$this->params['uid']])->asArray()->One();
        $data['rank_name']=Yii::$app->params['member']['rank'][$data['rank_id']];
        if($data['hospital_id'])
        {
            $hospital = Hospital::findOne($data['hospital_id']);
            $data['hospital_name'] = $hospital->name;
        }
        else
            $data['hospital_name'] = null;

        $result = ['code' => 200,'message'=>'个人信息','data'=>$data];
        return $result;

    }


    /**
     * 设置注册下一步
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionNext()
    {
        $model = new $this->modelClass(['scenario' => 'next']);
        $model->load($this->params, '');
        if(!$response = $model->editProfile())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
        {
            $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][0].$this->params['uid']);
            if($data)
            {
                $data= json_decode($data,true);
                unset($data['province']);
                $data['province'] = $this->params['province'];
                Yii::$app->cache->set(Yii::$app->params['redisKey'][0].$this->params['uid'],json_encode($data),2592000); // 更新缓存
            }
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        }

        return $result;
    }
    /**
     * 设置昵称
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionNickname()
    {
        $model = new $this->modelClass(['scenario' => 'setNickname']);
        $model->load($this->params, '');
        if(!$response = $model->changeNickname())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
    /**
     * 设置用户名
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionUsername()
    {
        $model = new $this->modelClass(['scenario' => 'setUsername']);
        $model->load($this->params, '');
        if(!$response = $model->changeUsername())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
    /**
     * 设置真实姓名
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionRealname()
    {
        $model = new $this->modelClass(['scenario' => 'setRealname']);
        $model->load($this->params, '');
        if(!$response = $model->changeRealname())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }

    /**
     * 设置性别
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionSex()
    {
        $model = new $this->modelClass(['scenario' => 'setSex']);
        $model->load($this->params, '');
        if(!$response = $model->changeSex())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }

    /**
     * 设置地区
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionRegion()
    {
        $model = new $this->modelClass(['scenario' => 'setRegion']);
        $model->load($this->params, '');
        if(!$response = $model->changeRegion())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];

        return $result;
    }

    /**
     * 设置药店名称
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionHospital()
    {
        $model = new $this->modelClass(['scenario' => 'setHospital']);
        $model->load($this->params, '');
        if(!$response = $model->changeHospital())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
    /**
     * 设置职称
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionRank()
    {
        $model = new $this->modelClass(['scenario' => 'setRank']);
        $model->load($this->params, '');
        if(!$response = $model->changeRank())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
     /**
     * 表单上传基础参数
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionToken()
    {
        $accessKey = Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];      
        $bucket = Yii::$app->params['qiniu']['bucket']; // 要上传的空间
        $domain = Yii::$app->params['qiniu']['domain']; // 七牛返回的域名
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        $key = 'images/user/'.time().'.jpg'; // 上传文件目录名images后面跟单独文件夹（ad为自定义）
        $result = ['code' => 200,'message'=>'token表单上传','data'=>['token'=>$token,'key'=>$key]];
        return $result; 
    }
      /**
     * 表单上传基础参数
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionUpload()
    {
        $avatar = $this->params['avatar'];
        $avatar='http://googlr.com';
        if(!$avatar)
        {
            $result = ['code' => -1,'message'=>'无效的文件','data'=>null];
            return $result; 
        }
        $avatar = Yii::$app->params['qiniu']['domain'].'/'.$avatar; // 完整地址
        $model=new $this->modelClass();
        $model->updateAll(['avatar'=>$avatar],'id=:id',array(':id'=>$this->uid));
        $result = ['code' => 200,'message'=>'上传成功','data'=>['avatar'=>$avatar]];
        return $result; 
    }
    public function actionList()
    { echo'test141';exit;
        $query = Article::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'title' => SORT_ASC,
                ]
            ],
        ]);
        return [
            'date' => date('Ymd'),
            'stories' => $provider->getModels(),
        ];
    }
    public function actionView($id = 0)
    {
        $article = Article::find()->where(['id' => $id])->with('data')->asArray()->one();
        return $article;
    }
    public function actionDelete($id)
    {
        echo(110);
    }
    private function _getStatusCodeMessage($status)
    {
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
    
    
}
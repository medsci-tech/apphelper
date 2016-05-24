<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\base\InvalidConfigException;
use api\common\models\member;
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\{UploadManager,BucketManager};

class FeedbackController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Feedback';

    protected function verbs(){
        return [
            'save'=>['POST'],
            'delete'=>['POST'],
        ];
    }
     /**
     * 提交反馈
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionSave()
    {
        $imageList = $this->params['imageList'] ?? '';
        $content = $this->params['content'] ?? '';
        if(!$imageList || !$content)
        {
            $result = ['code' => -1,'message'=>'请至少加点东西吧!','data'=>null];
            return $result; 
        }
        foreach($imageList as $key => $val)
            $imageList[$key] = Yii::$app->params['qiniu']['domain'].'/'.$val; // 完整地址
        
        $imageList = serialize($imageList);
        $model= new $this->modelClass();
        $model->uid =$this->uid;
        $model->content =$content;
        $model->imgurl =$imageList;
        $model->created_at = time();
        $model->save();
        $result = ['code' => 200,'message'=>'已收到您的反馈，感谢使用','data'=>null];
        return $result; 
    }   
    /**
     * 删除资源
     * @author by lxhui
     * @version [2010-05-23]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionDelete()
    {
        $accessKey = Yii::$app->params['qiniu']['accessKey'];
        $secretKey = Yii::$app->params['qiniu']['secretKey'];      
        $bucket = Yii::$app->params['qiniu']['bucket']; // 要上传的空间
        $domain = Yii::$app->params['qiniu']['domain']; // 七牛返回的域名
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        //你要测试的空间， 并且这个key在你空间中存在
        $key = $this->params['key'];
        //删除$bucket 中的文件 $key
        $err = $bucketMgr->delete($bucket, $key);
        if ($err)
             $result = ['code' => -1,'message'=>'删除失败!','data'=>null];
        else 
             $result = ['code' => 200,'message'=>'删除成功!','data'=>null];
        return $result; 
    }
    
}
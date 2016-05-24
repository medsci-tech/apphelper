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
use Qiniu\Storage\UploadManager;
use common\components\Helper;
class FeedbackController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Feedback';

    protected function verbs(){
        return [
            'save'=>['POST'],
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
        $model=new $this->modelClass();
        $model->uid =$this->uid;
        $model->content =$content;
        $model->imgurl =$imageList;
        $model->created_at = time();
        $model->save();
        $result = ['code' => 200,'message'=>'已收到您的反馈，感谢使用','data'=>null];
        return $result; 
    }   
    
}
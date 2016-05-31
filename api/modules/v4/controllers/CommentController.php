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
class CommentController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Comment';

    protected function verbs(){
        return [
            'save'=>['POST'],
            'delete'=>['POST'],
        ];
    }
     /**
     * 提交评论
     * @author by lxhui
     * @version [2010-05-31]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionSave()
    {
        $model = new $this->modelClass();
        $model->load($this->params, '');
        if(!$response = $model->saves())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'评论成功!','data'=>null];
        return $result; 
    }  
    
}
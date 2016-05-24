<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */
namespace api\modules\v4\controllers;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
class PraiseController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Praise';

    protected function verbs(){
        return [
            'index'=>['POST'],        
        ];
    }
    /**
     * 用户点赞
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionIndex()
    {
        $model = new $this->modelClass();
        $model->load($this->params, '');
        if(!$response = $model->saves())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
            return $result;
        }
        else
            $result = ['code' => 200,'message'=>'点赞成功!','data'=>null];
        return $result;
    }

    

}
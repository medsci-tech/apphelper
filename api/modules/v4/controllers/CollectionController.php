<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use common\models\Region;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\components\Helper;
use yii\base\InvalidConfigException;
class ResourceController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Collection';

    protected function verbs(){
        return [
            'index'=>['GET','POST'],
            'add'=>['POST'],
        ];
    }
    /**
     * 添加收藏
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionAdd()
    {
        $id = $this->params['id'];
        $where=['uid'=>$this->params['uid'],'rid'=>$id,'type'=>1];
        $model = $this->$this->modelClass();
        $result =$model::find($where)->where()->one();
        
        $result = ['code' => 200,'message'=>'药店列表','data'=>['isLastPage'=>true ,'list'=>$data]];
        return $result;
    }


}
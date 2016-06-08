<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\data\Pagination;

class MessageController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Message';

    protected function verbs()
    {
        return [
            'index' => ['POST'],
            'warn' => ['POST'],
            'read' => ['POST'],
        ];
    }
    /**
     * 消息列表
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $offset = $pagesize * ($page - 1); //计算记录偏移量
        $model = new $this->modelClass();
        //$model->updateAll(['isread'=>1],'touid=:touid',array(':touid'=>$this->uid));//更新信息已读状态
        
        $data = $model::find()
            ->select(['id','title','link_url'])
            ->where(['or','touid='.$this->uid,'type=1'])
            ->orderBy(['send_at'=>SORT_DESC]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);
        
        $result = ['code' => 200, 'message' => '消息列表!', 'data' => ['isLastPage' => $page >= $total_page ? true : false, 'list' => $model ? $model : null]];
        return $result;
    }
    
    /**
     * 消息提醒
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionWarn()
    {
        $model = new $this->modelClass();
        //$count = $model::find()->where(['touid'=>$this->uid, 'isread'=>0,'type'=>0])->count(); // 单推消息
        $count = $model::find()->where(['or', 'type=1', ['and', 'touid='.$this->uid,'isread=0']])->count(); 
        $result = ['code' => 200, 'message' => '消息提醒!', 'data' => ['count' => $count]];
        return $result;
    }
    /**
     * 消息提醒
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionRead()
    {
        $model = new $this->modelClass();
        $id=$this->params['id'];
        $model = $model::findOne($id);
        if(!$model)
        {
            $result = ['code' => 200, 'message' => '没有找到该消息记录!!', 'data' =>null];   
            return $result;
        }
        if($model->type==1) // 单推设置(群推无需设置属性)
        {
            $model->isread =1;
            $model->save();  
        }

        $result = ['code' => 200, 'message' => '消息已读!', 'data' =>null];
        return $result;
    }
}



<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use Yii;
use api\common\models\{Resource,Comment};
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

        $data = $model::find()
            ->select(['id','title','link_url','push_type','type','cid','isread'])
            //->where(['and', 'touid='.$this->uid, ['or', 'push_type=0', 'push_type=1']])
            ->where(['touid'=>$this->uid,'push_type'=>0])
            ->orderBy(['send_at'=>SORT_DESC])
            ->asArray()->all(); // 单推
       
        $res = $model->getFullWarn($this->uid);
        $res = $res['list']; // 全推消息
        $data = array_merge($data,$res);   
        ArrayHelper::multisort($data, ['id'], [SORT_DESC]);
        foreach($data as &$val)
        {
            $val['rid'] = null;
            if (in_array($val['type'], ['resource','exam']))
            {
                $comment = Comment::find()->select(['rid','exa_id'])->where(['id'=>$val['cid'],'type'=>$val['type']])->one();
                $val['rid']= $comment->rid; // 资源id
            }

            if(!$val['link_url'] && !$val['cid'])
                $val['link_url']=Yii::$app->params['wapUrl'].'/message/view/'.$val['id'];

            if($val['link_url'])
                $val['goType']= 'link';
            else
                $val['goType']= 'comment';
            
            $val['isread'] = $val['isread']>0  ? true : false;
            unset($val['push_type']);
        }
        $total_page = ceil(count($data)/$pagesize); // 总页数    
        $data = array_slice($data,$offset,$pagesize);
        $result = ['code' => 200, 'message' => '消息列表!', 'data' => ['isLastPage' => $page >= $total_page ? true : false, 'list' => $data ? $data : []]];
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
        $count = $model::find()->where(['touid'=>$this->uid, 'isread'=>0,'push_type'=>0])->count(); // 单推消息
        //$count = $model::find()->where(['or', 'push_type=1', ['and', 'touid='.$this->uid,'isread=0']])->count(); 
        $count_2 = 0; // 初始化全推消息数为0
        $res = $model->getFullWarn($this->uid);
        if($res)
            $count_2 = $res['count'];
           
        $result = ['code' => 200, 'message' => '消息提醒!', 'data' => ['count' => $count+$count_2]];
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
            $result = ['code' => 200, 'message' => '没有找到该消息记录!', 'data' =>null];   
            return $result;
        }
        if($model->push_type==0) // 单推设置(群推无需设置属性)
        {
            $model->isread =1;
            $model->save();  
        }
        else // 群推设置用户消息状态
        {
            $key = $id.'_'.$this->uid; // 每个消息的缓存键
            $keyValue = Yii::$app->redis->get($key);
            $keyValue = json_decode($keyValue,true);
            /* 设置已读状态 */
            if(is_array($keyValue) && $keyValue['isread']==0)
            {
                $keyValue = ['id'=>$id,'isread'=>1];
                Yii::$app->redis->set($key,json_encode($keyValue));  
            }
        }
        $result = ['code' => 200, 'message' => '消息已读!', 'data' =>null];
        return $result;
    }

}

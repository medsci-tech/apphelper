<?php
namespace api\common\models;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
class Message extends \yii\db\ActiveRecord
{
   /**
     * 获取全推读消息提醒
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $uid 登录用户id
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function getFullWarn($uid)
    {
        /* 获取全推未读消息 */
        $result = $this::find()->where(['push_type'=>1])->asArray()->orderBy(['id'=>SORT_DESC])->all();
        $count = 0; //消息总数
        if($result)
        {
            foreach($result as $val)
            {             
                $key = $val['id'].'_'.$uid; // 每个消息的缓存键
                $keyValue = Yii::$app->redis->get($key);
                $keyValue = json_decode($keyValue,true);
                if(!$keyValue)
                {
                    $keyValue = ['id'=>$val['id'],'isread'=>0];
                    Yii::$app->redis->set($key,json_encode($keyValue));
                }  
                if($keyValue['isread']==0)
                    $count++;
            }
        }
        $ids = ArrayHelper::getColumn($result, 'id') ?? null;   // 群推消息id集合     
        $list = ['count'=>$count,'data'=>$ids];
        return $list;       
    }   

}
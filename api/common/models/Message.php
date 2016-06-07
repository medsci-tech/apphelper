<?php
namespace api\common\models;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
class Message extends \yii\db\ActiveRecord
{
   /**
     * 获取全推未读消息提醒
     * @author by lxhui
     * @version [2010-05-21]
     * @param array $uid 登录用户id
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function getFullWarn($uid)
    {
        /* 获取全推未读消息 */
        $result = $this::find()->where('type=1')->asArray()->orderBy(['id'=>SORT_DESC])->all();
        $count = 0; //消息总数
        if($result)
        {
            foreach($result as $val)
            {
               $key = $val['id'].'_'.$uid; // 每个消息的缓存键
               $cacheData = ['id'=>$val['id'],'isread'=>0];
               $keyValue = Yii::$app->redis->get($key);
               if(!$keyValue)
               {
                 Yii::$app->redis->set($key,json_encode($cacheData));
                 $count++;
               }      
            }
            //$ids = ArrayHelper::getColumn($result, 'id');   
         
            $count = count($ids);
            $ids = implode('_', $ids);
            $key = $ids.'_'.$uid;
            $list = ['key'=>$key,'count'=>$count,'data'=>$result];
            print_r($list);
            //Yii::$app->redis->set($key,json_encode($list));
        }
        
    }   

}
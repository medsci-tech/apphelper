<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\{Exam,ExamLog,ExamLevel,Exercise,ExamClass};
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
class ExamController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Exam';

    protected function verbs(){
        return [
            'index'=>['POST'],
            'info'=>['POST'],
            'list'=>['POST'],
            'submit'=>['POST'],
            'analyze'=>['POST'],
        ];
    }   
    /**
     * 试卷列表
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        //self::cleanHistory(); // 清除脏数据
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $offset=$pagesize*($page - 1); //计算记录偏移量
        
        $model = new $this->modelClass();
        $where = ['status'=>1, 'publish_status'=>1,'recommend_status'=>1,'id'=>1];
        $orderBy= [ 'publish_time' => SORT_DESC, 'created_at' => SORT_DESC];
        $data = $model::find()->select(['id','name as title','minutes','imgurl',"if(type>0,total,LENGTH(exe_ids) - LENGTH(REPLACE(exe_ids,',',''))+1)AS total"])->OrderBy($orderBy)->where($where)->asArray()->all(); //所有推荐资源
        /*  查询历史考试记录  */
        if($data)
        {
            $ids = ArrayHelper::getColumn($data, 'id');//试卷id集合
            foreach($data as &$val){
                $maxTime = $val['minutes']*60; //最大时间       
                $where =['exa_id'=>$val['id'],'uid'=>$this->uid];
                $log = ExamLog::find()->OrderBy(['answers'=>SORT_DESC])->where($where)->asArray()->one();//最佳答题记录
                if($log['status']==1)
                {
                    /* 根据成绩计算等级 */
                    $rate = intval($log['answers']/$val['total']*100); //正确率 
                    $level = self::getLevel($val['id'],$rate,$val['total']); // 根据正确率返回等级
                    $val['labelName']='历史最佳';
                    $val['labelValue']= $level;
                }
                else
                {
                   if($log['start_time']>0 && !$log['end_time']) // 未提交试卷
                   {
                        ExamLog::deleteAll('uid = :uid AND exa_id = :exa_id AND status=0 AND unix_timestamp(now())-start_time > :maxTime', [':uid' =>$this->uid,'exa_id'=>$val['id'],':maxTime' => $maxTime]); //删除历史脏数据  
                        $val['labelName']='进行中';
                        $val['labelValue']= null;
                   } 
                  elseif(!$log['start_time'] && !$log['end_time']) //未参与考试
                        $val['labelName']=$val['labelValue']=null;  
                }
  
                $val['status'] = $log['status']==0 && $log['start_time']>0 ? '2' : $log['status'];
            }
        }
        
        $total_page = ceil(count($data)/$pagesize); // 总页数    
        $data = array_slice($data,$offset,$pagesize);
        $result = ['code' => 200,'message'=>'试卷列表','data'=>['isLastPage'=>$page>=$total_page ? true : false ,'list'=>$data]];
        return $result;
    }   
    
    /**
     * 试卷介绍
     * @author by lxhui
     * @version [2010-05-25]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionInfo()
    {
        $id = self::checkId();
        $data = Exam::find()->select(['name','minutes',"if(type>0,total,LENGTH(exe_ids) - LENGTH(REPLACE(exe_ids,',',''))+1)AS total",'about'])->where(['id'=>$id])->asArray()->one();
        $examLevel = ExamLevel::find()->select(['level'])->where(['exam_id'=>$id])->asArray()->all();
        $levels = ArrayHelper::getColumn($examLevel, 'level');
        if($levels)
            $levels = implode('/',$levels);
        else
            $levels=null;
        $data['levels']=$levels;
        $data['id']=$id;
        $result = ['code' => 200,'message'=>'试卷详情','data'=>$data];
        return $result;
    }
    
     /**
     * 试卷答题列表
     * @author by lxhui
     * @version [2010-05-25]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionList()
    {
        $id = self::checkId();
        /* 获取缓存试题列表 */
        $data = self::getExerciseById($id);
        $result = ['code' => 200,'message'=>'试卷题目列表','data'=>$data];
        return $result;
    }
    
    /**
     * 试卷提交
     * @author by lxhui
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionSubmit()
    {  
        $id = self::checkId();
        $data = self::infoExam($id);
        $max =$data['minutes']*60; //考试时间转秒
       /* 检查考试时间是否过期 */
        $lastModel = self::lastExam($id);
        $timeLeft =  time()-$lastModel->start_time; // 距离当前生剩余时间
        if($timeLeft>$max) // 如果已经过期(离线不适用)
        {
            //$result = ['code' => -1,'message'=>'考试时间已过期!','data'=>null];
            //return $result;
        }
        $optionList= $this->params['optionList'] ?? [];// 客户端提交的试题
        
        /* 处理提交试卷 */
        if($data['type']==1) // 随机出题
        {
            $exe_ids = array_keys($optionList);
            $examlist = Exercise::find()->select(['id','type','question','option','answer'])->where(['id'=>$exe_ids,'status'=>1])->asArray()->all();  
        }
        else
            $examlist =self::getById($id,$data['exe_ids']);
       
        $examlist = ArrayHelper::map($examlist, 'id', 'answer');  

        $i=0; // 答题正确数
        foreach($optionList as $key=> $val)
        {  
            if($val)
                $answer = implode(',',$val);
            if(isset($examlist[$key]) && $answer==$examlist[$key])
                $i++; //统计正确回答题目
        }
        $exam_total = $data['type']==0 ? substr_count($data['exe_ids'],',')+1 : count($optionList); // 题目总数
        /* 保存考试记录 */
        $model = self::lastExam($id);
        $model->exa_id =$id;
        $model->status =1;
        $model->uid =$this->uid;
        $model->answer_ids = serialize($optionList);
        $model->answers =$i;
        $model->end_time =time();
        $model->save();
        /* 根据成绩计算等级 */
        $rate = $exam_total>0 ? intval($i/$exam_total)*100 : 0; //正确率     
        $level = self::getLevel($id,$rate,$exam_total); // 根据正确率返回等级
        $mins = intval( $timeLeft / 60 ); //分钟
        $secs = $timeLeft % 60; //秒
        $times = $mins.':'.$secs;     
        //ExamLog::deleteAll('id < :id AND uid = :uid AND exa_id = :exa_id AND status=0', [':id' => $model->id,':uid' =>$this->uid,'exa_id'=>$id]); //删除历史脏数据
        Yii::$app->cache->delete(Yii::$app->params['redisKey'][5].$id.'_'.$this->uid);  //删除本试卷最后历史记录
        $result = ['code' => 200,'message'=>'提交成功!','data'=>['times'=>$times,'labelName'=>'历史最佳','labelValue'=>$level,'level'=>$level]];
        return $result; 
    } 
    
    /**
     * 试卷解析
     * @author by lxhui
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionAnalyze()
    {   
        $id = self::checkId();
        $res = self::infoExam($id);
        /* 查找用户下的最新历史考试记录 */
        $historyData = self::history($id);
        if(!$historyData)
        {
            $result = ['code' => -1,'message'=>'没有任何考题记录!','data'=>null];
            return $result;  
        }
        $answers = unserialize($historyData['answer_ids']); // 用户答题记录完整试题集合）
        $exe_ids = array_keys($answers);
        
        if($res['type']==1) 
            $data = json_decode(Yii::$app->redis->get(Yii::$app->params['redisKey'][8].$id.'_'.$this->uid),true);
            //$data = Exercise::find()->select(['id','type','question','option','answer','resolve'])->where(['id'=>$exe_ids,'status'=>1])->asArray()->all();  
        else
            $data =self::getById($id,$res['exe_ids']);

        $data = ArrayHelper::index($data, 'id');
        foreach($data as $key=>&$val)
        { 
            if($answers[$key])
                $answer = implode(',',$answers[$key]);
            else
                $answer=null;
            if($val['answer']==$answer)  // 答题正确时
                $val['isRight'] = true;
            else
                $val['isRight'] = false;          
            $val['doanswer'] = $answer;
            if($res['type']==1) 
                $val['option'] = unserialize($val['option']);  
            
        }
        $result = ['code' => 200,'message'=>'试题解析!','data'=>  array_values($data)];
        return $result; 
    }
    /**
     * 最后提交的历史试卷
     * @author by lxhui
     * @param $id 试卷id
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function history($id)
    {
        $key = Yii::$app->params['redisKey'][5].$id.'_'.$this->uid;
        $data = json_decode(Yii::$app->cache->get($key),true);
        if(!$data)
        {
            $data = ExamLog::find()->where(['uid'=>$this->uid,'status'=>1,'exa_id'=>$id])->OrderBy(['id'=>SORT_DESC])->asArray()->one();
            Yii::$app->cache->set($key,json_encode($data),2592000);   
        }
        return $data;
    }
    /**
     * 检查试卷id
     * @author by lxhui
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function checkId()
    {
        $id= $this->params['id'] ?? '';
        if(!$id)
        {   
            $result = ['code' => -1,'message'=>'缺少试卷对象id!','data'=>null];
            echo(json_encode($result));exit;
        }
        return $id;      
    }
    
    /**
     * 根据试卷id返回所有题目列表
     * @author by lxhui
     * @param $id 
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function getExerciseById($id)
    {
        $data = self::infoExam($id);
        $examlog = new ExamLog();
        $log = $examlog::find()->OrderBy(['id'=>SORT_DESC,'uid'=>$this->uid])->where(['exa_id'=>$id,'uid'=>$this->uid])->asArray()->one();//最后答题记录
        if($log['start_time']>0 && !$log['end_time']) // 未提交试卷
            $beginStatus = false; //继续考试
        else
            $beginStatus = true; // 开始考试
       /* 记录试卷考试开始时间 */ 
        if($beginStatus)
        {
            /* 记录开始答题时间 */
           $examlog->exa_id = $id;
           $examlog->uid =$this->uid;
           $examlog->start_time = time();
           $examlog->save();    
        }    
        if($data['type']==0) // 自定义出题
        {
            $cacheData = Yii::$app->cache->get(Yii::$app->params['redisKey'][4].$id);
            $cacheData = json_decode($cacheData,true);
            if($cacheData)
                  return $cacheData;          
        }       
        if($data['type']==1) // 随机出题   
        {
            if(!$beginStatus)
            {
                $data = Yii::$app->redis->get(Yii::$app->params['redisKey'][8].$id.'_'.$this->uid); // 获取随机缓存试题信息
                if($data)
                    $data = json_decode($data,true);
                else
                    $data= null;
            }
            else
                $data =self::randExam($id,$data['class_id'],$data['total']); // 重新发布随机试卷
        }
            
        else // 自定义出题
        {
            if($data['exe_ids'])
                $exe_ids = explode (',', $data['exe_ids']);

            $data = Exercise::find()->select(['id','type','question','option','answer','resolve'])->where(['id'=>$exe_ids,'status'=>1])->asArray()->all();           
        }
        foreach($data as &$val)
            $val['option'] = unserialize($val['option']);  
        if($data['type']==0) // 自定义
            Yii::$app->cache->set(Yii::$app->params['redisKey'][4].$id,json_encode($data),2592000); // 缓存试题列表 

        return $data;      
    }
    
    /**
     * 根据试卷id返回所有自定义题目列表
     * @author by lxhui
     * @param $id 
     * @param $exe_ids 题目id集合 
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function getById($id,$exe_ids)
    {
        $exe_ids = explode (',', $exe_ids); 
        $data = Exercise::find()->select(['id','type','question','option','answer','resolve'])->where(['id'=>$exe_ids,'status'=>1])->asArray()->all();
        foreach($data as &$val)
            $val['option'] = unserialize($val['option']);
        return $data;      
    }
    
    /**
     * 根据试卷id返回试卷所有等级
     * @author by lxhui
     * @param $id 试卷id
     * @param $rate 正确答题率
     * @param $total 题目总数
     * @version [2010-05-30]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function getLevel($id,$rate=0,$total=1)
    {
        $result= ExamLevel::find()->where(['exam_id'=>$id])->asArray()->all();
        $map = ArrayHelper::map($result, 'rate', 'level');   
        if($map)
            ksort($map);  
        $minRate = $map ? current($map) : 0;// 最小等级
        
        foreach($result as &$data)
        {
            switch ($data['condition'])
            {
                case 0: // 等于
                  if($rate==$data['rate'])
                      $level = $data['level'];
                  break;
                case 1://大于等于
                    if($rate>=$data['rate'])
                        $level = $data['level'];
                  break;
                case -1:// 小于
                    if($rate<$data['rate'])
                        $level = $data['level'];
                  break;
                default:
                    $level = $minRate;           
            }
        }
        return $level ?? $minRate;
    }
    
     /**
     * 最后未提交的历史试卷
     * @author by lxhui
     * @param $id 试卷id
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function lastExam($id)
    {
        $model = ExamLog::find()->where(['uid'=>$this->uid,'status'=>0,'exa_id'=>$id])->OrderBy(['id'=>SORT_DESC])->one();     
        if(!$model)
            $model = new ExamLog();
        return $model;
    }
     /**
     * 返回随机类型试卷题
     * @author by lxhui
     * @param $id 试卷id
     * @param $class_id 分类id
     * @param $total 出题总数
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function randExam($id,$class_id,$total)
    {
        $connection = \Yii::$app->db;
        $table = Exercise::tableName();
        $model = ExamClass::findOne($class_id);
        $where = "t1.id >= t2.id and t1.status=1";
        if($class_id)
        {
            if($model->parent) 
            {
                $res = ExamClass::find()->select('id')->where(['parent'=>$model->parent,'status'=>1])->asArray()->all();
                $ids = ArrayHelper::getColumn($res, 'id');
                $ids = implode(',',$ids);
                $where.= " and t1.category in($ids)";
            }
            else
                $where.= " and t1.category =".$class_id;  
        }
        
        $sql ="SELECT t1.id,t1.type,t1.question,t1.option,t1.answer,resolve FROM $table AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM $table)-(SELECT MIN(id) FROM $table))+(SELECT MIN(id) FROM $table)) AS id) AS t2 WHERE $where  ORDER BY t1.id LIMIT $total";  
        $command = $connection->createCommand($sql);
        $list = $command->queryAll();
        if(!$list) 
            return $this->randExam($id,$class_id,$total);
        
        /* 缓存随机试卷 */
        Yii::$app->redis->set(Yii::$app->params['redisKey'][8].$id.'_'.$this->uid,json_encode($list)); // 缓存试题信息    
        return $list;
    } 
    /**
     * 返回试卷基本信息
     * @author by lxhui
     * @param $id 试卷id
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function infoExam($id)
    {
        $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][7].$id);
        $data = json_decode($data,true); 
        if(!$data)
        {
            $data = Exam::find()->select(['exe_ids','minutes','type','total','class_id'])->where(['id'=>$id])->asArray()->one();  
            Yii::$app->cache->set(Yii::$app->params['redisKey'][7].$id,json_encode($data),2592000); // 缓存试题信息 
        }
        return $data;
    }
    /**
     * 删除过期的未提交试卷
     * @author by lxhui
     * @param $id 试卷id
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function cleanHistory()
    {
        $result = ExamLog::find()->where(['status'=>0,'uid'=>$this->uid])->asArray()->All();
        foreach($result as $val)
        {
            $data = self::infoExam($val['exa_id']);
            $max =$data['minutes']*60; //考试时间转秒  
            $timeLeft =  time()-$val['start_time']; // 距离当前生剩余时间
            if($timeLeft>$max) // 如果已经过期
                ExamLog::deleteAll('id = :id', [':id' => $val['id']]); //删除历史脏数据          
        }
        return ;
    }
 
}
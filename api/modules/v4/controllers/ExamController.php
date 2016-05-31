<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\{Exam,ExamLog,ExamLevel,Exercise};
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
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $offset=$pagesize*($page - 1); //计算记录偏移量
        
        $model = new $this->modelClass();
        $where = ['status'=>1, 'publish_status'=>1,'recommend_status'=>1];
        $orderBy= [ 'publish_time' => SORT_DESC, 'created_at' => SORT_DESC];
        $data = $model::find()->select(['id','name as title','imgurl',"LENGTH(exe_ids) - LENGTH(REPLACE(exe_ids,',','')) as total"])->OrderBy($orderBy)->where($where)->asArray()->all(); //所有推荐资源
        /*  查询历史考试记录  */
        if($data)
        {
            $ids = ArrayHelper::getColumn($data, 'id');//试卷id集合
            $examLog = new ExamLog();  
            foreach($data as &$val){
                $where =['exa_id'=>$val['id']];//'uid'=>$this->uid 临时去掉便于测试
                $log = $examLog::find()->OrderBy(['id'=>SORT_DESC])->where($where)->asArray()->one();//最后答题记录
                if($log['status']==1)
                {
                    $val['labelName']='历史最佳';
                    $val['labelValue']= '学霸';
                }
                else
                  $val['labelName']=$val['labelValue']=null;  
  
                $val['status'] = $log['status']==0 && $log['start_time']>0 ? '2' : $log['status'];
                if (in_array($val['status'], [1,2]))
                    $val['icon'] = 'http://o7f6z4jud.bkt.clouddn.com/images/level/1.jpg?imageView2/2/w/20/h/20/format/jpg/interlace/1/q/85';
                else
                    $val['icon'] = null; 
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
        $data = Exam::find()->select(['name','minutes',"LENGTH(exe_ids) - LENGTH(REPLACE(exe_ids,',','')) as total",'about'])->where(['id'=>$id])->asArray()->one();
        $examLevel = ExamLevel::find()->select(['level'])->where(['exam_id'=>$id])->asArray()->all();
        $levels = ArrayHelper::getColumn($examLevel, 'level');
        if($levels)
            $levels = implode('/',$levels);
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
        /* 记录开始答题时间 */
        $examlog = new ExamLog();
        $examlog->exa_id =$id;
        $examlog->uid =$this->uid;
        $examlog->start_time = time();
        $examlog->save();
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
        $data = Exam::find()->select(['exe_ids','minutes'])->where(['id'=>$id])->asArray()->one();
        $max =$data['minutes']*60; //考试时间转秒
       /* 检查考试时间是否过期 */
        $lastModel = self::lastExam($id);
        $timeLeft =  time()-$lastModel->start_time; // 距离当前生剩余时间
        if($timeLeft>$max) // 如果已经过期
        {
            $result = ['code' => -1,'message'=>'考试时间已过期!','data'=>null];
            return $result;
        }

        /* 处理提交试卷 */
        $exe_ids = explode (',', $data['exe_ids']); //题库id集合
        $examlist = self::getExerciseById($id); // 获取试卷所有题目
        $examlist = ArrayHelper::map($examlist, 'id', 'answer');
        $exam_total = substr_count($data['exe_ids'],',')+1; //题目总数
 
        $optionList= $this->params['optionList'];
        $i=0; // 答题正确数
        foreach($optionList as $key=> $val)
        {
            $answer = implode(',',$val); 
            if($answer==$examlist[$key])
                $i++; //统计正确回答题目
        }
        /* 保存考试记录 */
        $model = new ExamLog();
        $model->exa_id =$id;
        $model->status =1;
        $model->uid =$this->uid;
        $model->answer_ids = serialize($optionList);
        $model->answers =$i;
        $model->start_time =time()-$times;
        $model->end_time =time();
        $model->save();
        /* 根据成绩计算等级 */
        $rate = intval($i/$exam_total)*100; //正确率     
        $level = self::getLevel($id,$rate,$exam_total); // 根据正确率返回等级
        $mins = intval( $times / 60 ); //分钟
        $secs = $times % 60; //秒
        $times = $mins.':'.$secs;
        $result = ['code' => 200,'message'=>'提交成功!','data'=>['times'=>$times,'level'=>$level]];
        return $result; 
    } 
    
        /**
     * 试卷提交
     * @author by lxhui
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionAnalyze()
    {  
        $id = self::checkId();
        /* 查找用户下的最新历史考试记录 */
        $historyData = self::history($id);
        $answers = unserialize($historyData['answer_ids']); // 用户答题记录
        /* 获取缓存试题列表 */
        $data = self::getExerciseById($id);
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
            $val['donswer'] = $answer;
        }
        
        $comment = ['nickname' =>'哇哈哈','avatar'=>'http://1.jpg','title'=>'你好吗'];
        $result = ['code' => 200,'message'=>'试题解析!','data'=>['list'=>$data,'comment'=>$comment]];
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
        $data = json_decode($key,true);
        if(!$data)
        {
            $data = ExamLog::find()->where(['uid'=>$this->uid,'status'=>1])->OrderBy(['id'=>SORT_DESC])->asArray()->one();
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
        $data = json_decode(Yii::$app->cache->get(Yii::$app->params['redisKey'][4].$id),true);
        if(!$data)
        {
            $data = Exam::find()->select(['exe_ids'])->where(['id'=>$id])->asArray()->one();
            if($data['exe_ids'])
                $exe_ids = explode (',', $data['exe_ids']);

            $data = Exercise::find()->select(['id','type','question','option','answer'])->where(['id'=>$exe_ids])->asArray()->all();
            foreach($data as &$val)
                $val['option'] = unserialize($val['option']);

            Yii::$app->cache->set(Yii::$app->params['redisKey'][4].$id,json_encode($data),2592000); // 缓存试题列表          
        } 
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
                    $level = '未定义';           
            }
        }
        return $level;
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
        $model = ExamLog::find()->where(['uid'=>$this->uid,'status'=>0])->OrderBy(['id'=>SORT_DESC])->one();
        if($model)
            ExamLog::deleteAll('id < :id AND uid = :uid AND exa_id = :exa_id AND status=0', [':id' => $model->id,':uid' =>$this->uid,'exa_id'=>$id]); //删除历史脏数据
        
        return $model;
    }

}
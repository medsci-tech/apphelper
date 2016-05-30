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
                    $val['labelValue']= $log['level'];
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
        
        $data = Exam::find()->select(['exe_ids'])->where(['id'=>$id])->asArray()->one();
        if($data['exe_ids'])
            $exe_ids = explode (',', $data['exe_ids']);
        
        $data = Exercise::find()->select(['id','type','question','option','answer'])->where(['id'=>$exe_ids])->asArray()->all();
        foreach($data as &$val)
            $val['option'] = unserialize($val['option']);

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
       /* 检查考试时间是否过期（考试进度会存在时间误差） */
//        $lastModel = self::history($id);
//        $timeLeft =  time()-$lastModel->start_time; // 距离当前生剩余时间
//        if($timeLeft>$max) // 如果已经过期
//        {
//            $result = ['code' => -1,'message'=>'考试时间已过期!','data'=>null];
//            return $result;
//        }
        /* 处理提交试卷 */
        $optionList= $this->params['optionList'];
        //更新试卷提交状态
        $model = self::history($id);
   
        $model->status =1;
        $model->end_time =time();
        $model->save();
         
        $result = ['code' => 200,'message'=>'提交成功!','data'=>['times'=>'20:50','level'=>'高级学霸']];
        return $result;
    
    } 
    /**
     * 最后未提交的历史试卷
     * @author by lxhui
     * @param $id 试卷id
     * @version [2010-05-29]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function history($id)
    {
        $model = ExamLog::find()->where(['uid'=>$this->uid,'status'=>0])->OrderBy(['id'=>SORT_DESC])->one();
        if($model)
            ExamLog::deleteAll('id < :id AND uid = :uid AND exa_id = :exa_id AND status=0', [':id' => $model->id,':uid' =>$this->uid,'exa_id'=>$id]); //删除历史脏数据
        
        return $model;
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

}
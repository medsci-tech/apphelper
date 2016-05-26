<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\{Exam,ExamLog,ExamLevel};
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
class ExamController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Exam';

    protected function verbs(){
        return [
            'index'=>['POST'],
            'add'=>['POST'],
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
        $exams = $model::find()->select(['id','name as title','imgurl',"LENGTH(exe_ids) - LENGTH( REPLACE(exe_ids,',','')) as total",'publish_time'])->OrderBy($orderBy)->where($where)->asArray()->all(); //所有推荐资源
        /*  查询历史考试记录  */
        if($exams)
        {
            $ids = ArrayHelper::getColumn($exams, 'id');//试卷id集合
            $examLog = new ExamLog();
            
            foreach($exams as &$val){
                $log = $examLog::find()->OrderBy(['id'=>SORT_DESC])->where(['exa_id'=>$val['id'],'uid'=>$this->uid])->asArray()->one();//最后答题记录
                $status = $log['status']==1 ? '已完成' : ($log['status']==0 && $log['start_time']>0 ? '进行中' : '');
                $val['status'] = $status;
                if($status) // 已经完成的试卷
                {
                    /* 评定等级 */
                    
                }
            }
        }

        exit;
        
        
        $result = ['code' => 200,'message'=>'试卷列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
        return $result;
    }
    
    /**
     * 查询答题评定等级
     * @param $ids 答卷id
     * @author by lxhui
     * @version [2010-05-25]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    private function getLevel($ids=[])
    {
        $examLevel = new ExamLevel();
        
    }
  

}
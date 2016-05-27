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

}
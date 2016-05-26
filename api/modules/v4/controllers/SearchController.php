<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use Yii;
use api\common\models\{Ad, Resource,Exam,ResourceClass};
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
class SearchController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Resource';

    protected function verbs(){
        return [
            'index'=>['POST'],      
            'remind'=>['POST'],   
        ];
    }

    /**
     * 搜索列表
     * @author by lxhui
     * @version [2010-05-23]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionIndex()
    {
        $keyword= $this->params['keyword'] ?? '';
        if(!$keyword)
        {
            $result = ['code' => -1,'message'=>'关键词不能为空!','data'=>null];
            return $result;  
        }
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $offset=$pagesize*($page - 1); //计算记录偏移量
        
        $resources = $exams =[];//初始化
        $where = ['status'=>1, 'publish_status'=>1,'recommend_status'=>1];
        $orderBy= [ 'publish_time' => SORT_DESC, 'created_at' => SORT_DESC];
        $resources = Resource::find()->select(['id','rid','title','imgurl','views','publish_time'])->OrderBy($orderBy)->where($where)->andWhere(['like', 'title', $keyword])->asArray()->all(); //所有推荐资源
        if($resources)
        {
            $rids = ArrayHelper::getColumn($resources, 'rid'); // 关联资源分类id  
            $rids_str = implode(',', $rids);   
            $sql = "SELECT id,parent FROM ".ResourceClass::tableName()." where id in($rids_str) order by field(id,$rids_str)";
            $resource_class = ResourceClass::findBySql($sql)->asArray()->all();         
            $resource_class = ArrayHelper::map($resource_class, 'id', 'parent');
            /* 组合信息列表 */
            $count= count($resources); 
            for($i=0;$i<$count;$i++)
            {  
                $resources[$i]['labelName']='参与人数';
                $resources[$i]['labelValue']=$resources[$i]['views'];
                $resources[$i]['classname']=constant("CLASSNAME")[$resource_class[$resources[$i]['rid']]];
                $resources[$i]['type']='article';
                unset($resources[$i]['rid'],$resources[$i]['views']);
            }  
        }       
        $exams = Exam::find()->select(['id','name as title','imgurl',"LENGTH(exe_ids) - LENGTH( REPLACE(exe_ids,',','')) as total",'publish_time'])->OrderBy($orderBy)->where($where)->andWhere(['like', 'name', $keyword])->asArray()->all(); //所有推荐资源
        if($exams)
        {
           foreach($exams as &$val)
           {
                $val['classname']='考卷';
                $val['labelName']='题目总数';
                $val['labelValue']=$val['total'];
                $val['type']='exam';
                unset($val['total']);
           }
        }
        $data = array_merge($resources,$exams);   
        ArrayHelper::multisort($data, ['publish_time'], [SORT_DESC]);
   
        
        $total_page = ceil(count($data)/$pagesize); // 总页数    
        $data = array_slice($data,$offset,$pagesize);
        
        $result = ['code' => 200,'message'=>'推荐列表','data'=>['isLastPage'=>$page>=$total_page ? true : false,'list'=>$data]];
        return $result;
       
    }
     /**
     * 提醒关键词
     * @author by lxhui
     * @version [2010-05-23]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */  
    public function actionRemind()
    {
        $keyword= $this->params['keyword'] ?? ''; // 当前页码
        if($keyword)
        {
            $model= new $this->modelClass();
            $results =$model
                ->find()
                ->select(['title'])
                ->orderBy(['publish_time' => SORT_DESC])
                ->where(['like', 'title', $keyword])
                ->andWhere(['status'=>1,'publish_status'=>1])
                ->limit(5)
                ->asArray()->all();
            $data = ArrayHelper::getColumn($results, 'title');
            if($data)
            {
                foreach($data as $k=>$v)
                    $data[$k]=['keyword'=> $v];  
            }
            else
              $data = null;  
        }        
        $data=[
           ['keyword'=>'甲状腺'],
           ['keyword'=>'糖凝胶囊'],
           ['keyword'=>'胰岛素'],
          ];  // 临时测试   
        $result = ['code' => 200,'message'=>'关键词提醒','data'=>$data];
        return $result;
    }

}
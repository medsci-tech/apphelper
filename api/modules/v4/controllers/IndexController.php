<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\{Ad, Resource,Exam,ResourceClass};
use Yii;
use yii\helpers\ArrayHelper;
use common\components\Helper;
use yii\base\InvalidConfigException;
class IndexController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Member';

    protected function verbs(){
        return [
            'index'=>['GET','POST'],
            'ad'=>['POST'],
            'rank'=>['POST'],
            'reg'=>['POST'],
            'nickname'=>['POST'],
            'username'=>['POST'],
            'realname'=>['POST'],
        ];
    }
    /**
     * 轮播图列表
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionAd()
    {   
        $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][1]); //获取缓存
        $data = json_decode($data,true); 
        if(!$data)
        {
            /* 查询数据库 */
            $model = new Ad();
            $data = $model::find()
                ->select(['id','title','linkurl','imgurl',"if(attr_from>1,'exam',IF(attr_from>0,'article',null))AS type",])
                ->where(['status' => 1])
                ->orderBy(['created_at' => SORT_DESC, 'sort' => SORT_DESC])
                ->asArray()
                ->all();
            Yii::$app->cache->set(Yii::$app->params['redisKey'][1],json_encode($data),2592000);
        }
        $result = ['code' => 200, 'message' => '轮播图', 'data' => $data];
        return $result;
    } 
    /**
     * App首页
     * @author by lxhui
     * @version [2010-05-24]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $offset=$pagesize*($page - 1); //计算记录偏移量
        $data = json_decode(Yii::$app->cache->get(Yii::$app->params['redisKey'][3]),true);// 从缓存获取数据
        if(!$data)
        {
            $resources = $exams =[];
            $where = ['status'=>1, 'publish_status'=>1,'recommend_status'=>1];
            $orderBy= [ 'publish_time' => SORT_DESC, 'created_at' => SORT_DESC];
            $resources = Resource::find()->select(['id','rid','title','imgurl','views','publish_time'])->OrderBy($orderBy)->where($where)->asArray()->all(); //所有推荐资源
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
            $exams = Exam::find()->select(['id','name as title','imgurl',"LENGTH(exe_ids) - LENGTH( REPLACE(exe_ids,',','')) as total",'publish_time'])->OrderBy($orderBy)->where($where)->asArray()->all(); //所有推荐资源
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
            Yii::$app->cache->set(Yii::$app->params['redisKey'][3],json_encode($data),2592000);        
        }
        
        $total_page = ceil(count($data)/$pagesize); // 总页数    
        $data = array_slice($data,$offset,$pagesize);
        
        $result = ['code' => 200,'message'=>'推荐列表','data'=>['isLastPage'=>$page>=$total_page ? true : false,'list'=>$data]];
        return $result;
    }
    /**
     * 职称列表
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionRank()
    {
        $p = $this->params['p'] ?? 1; // 当前页码
        $data = Yii::$app->params['member']['rank'];
        $res=[];
        foreach($data as $k=>$v)
            $res[$k]=['rank_id'=>$k,'rank_name'=> $v];
        $result = ['code' => 200,'message'=>'职称列表','data'=>$res];
        return $result;
    }
    /**
     * 设置昵称
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionNickname()
    {
        $model = new $this->modelClass(['scenario' => 'setNickname']);
        $model->load($this->params, '');
        if(!$response = $model->changeNickname())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
    /**
     * 设置用户名
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionUsername()
    {
        $model = new $this->modelClass(['scenario' => 'setUsername']);
        $model->load($this->params, '');
        if(!$response = $model->changeUsername())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
    /**
     * 设置真实姓名
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionRealname()
    {
        $model = new $this->modelClass(['scenario' => 'setRealname']);
        $model->load($this->params, '');
        if(!$response = $model->changeRealname())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }
    /**
     * 完成注册提交
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionReg()
    {

        $model = new $this->modelClass(['scenario' => 'next']);
        $model->load($this->params, '');
        if(!$response = $model->changeRealname())
        {
            $message = array_values($model->getFirstErrors())[0];
            $result = ['code' => -1,'message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => 200,'message'=>'设置成功','data'=>null];
        return $result;
    }

    /**
     * 版本升级提醒
     * @author by lxhui
     * @version [2010-05-15]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionVersion()
    {
        $result = ['code' => 200,'message'=>'版本升级提醒','data'=>['lastVersion'=>'4.1','download'=>'http://baidu.com/updown/893.apk','isUpdate'=>true,'isCompel'=>true]];
        return $result;
    }

}
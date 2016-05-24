<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use common\models\{Resource,ResourceClass};
use Yii;
use yii\helpers\ArrayHelper;
use common\components\Helper;
use yii\base\InvalidConfigException;
use yii\data\Pagination;
class CollectionController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Collection';
    protected function verbs(){
        return [
            'index'=>['POST'],
            'add'=>['POST'],
        ];
    } 
    /**
     * 收藏列表
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionIndex()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset=$pagesize*($page - 1); //计算记录偏移量
        $model = new $this->modelClass();
        $result = $model::find()
            ->select(['rid'])
            ->andWhere(['uid' => $this->uid]);    
        $pages = new Pagination(['totalCount' =>$result->count(), 'pageSize' => $pagesize]);
        $results = $result->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($result->count()/$pagesize);
        if($results)
        {
            $rids = ArrayHelper::getColumn($results, 'rid'); // 关联资源id 
            $rids_str = implode(',', $rids);           
            // 用原生 SQL 语句检索(yii2 ORM不支持field排序)
            $sql = "SELECT id,rid,title,imgurl,views FROM ".Resource::tableName()." where id in($rids_str) order by field(id,$rids_str)";
            $data = Resource::findBySql($sql)->asArray()->all();           
            $rids_2 = ArrayHelper::getColumn($data, 'rid'); // 关联资源分类id  
            $rids_str2 = implode(',', $rids_2);   
            $sql = "SELECT id,parent FROM ".ResourceClass::tableName()." where id in($rids_str2) order by field(id,$rids_str2)";
            $resource_class = ResourceClass::findBySql($sql)->asArray()->all(); 
            foreach($resource_class as &$val)
            {
                $val['classname']= constant("CLASSNAME")[$val['parent']];
                unset($val['parent']);
            }  
            /* 组合信息列表 */
            $count= count($data);
            for($i=0;$i<$count;$i++)
            {
                $data[$i]['labelName']='参与人数';
                $data[$i]['labelValue']=$data[$i]['views'];
                $data[$i]+=$resource_class[$i];
                unset($data[$i]['rid'],$data[$i]['views']);
            }
            Yii::$app->cache->set(Yii::$app->params['redisKey'][6],json_encode($data),2592000);
        }   
    
        $result = ['code' => 200,'message'=>'收藏列表','data'=>['isLastPage'=>$page>=$total_page ? true : false ,'list'=>$data ?? null]];
        return $result;
    }
    /**
     * 添加收藏
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionAdd()
    {
        $id = $this->params['id'];
        $where=['uid'=>$this->uid,'rid'=>$id,'type'=>1];
        $model = new $this->modelClass();
        $result =$model::find($where)->where($where)->one();
        if($result)
        {
            $result = ['code' => -1,'message'=>'已经收藏过!','data'=>null];
            return $result;           
        }
        $model->uid=$this->uid;
        $model->rid=$id;
        $model->created_at =time();
        $result = $model->save();
        if($result)
        {
            $result = ['code' => 200,'message'=>'收藏成功!','data'=>null];
            return $result;  
        }
    }
}
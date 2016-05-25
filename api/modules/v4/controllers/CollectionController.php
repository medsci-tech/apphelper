<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use Yii;
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
            $data = $this->getData($results);
            //Yii::$app->cache->set(Yii::$app->params['redisKey'][6],json_encode($data),2592000); 
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
        if(!$id)
        {
            $result = ['code' => -1,'message'=>'缺少收藏对象id!','data'=>null];
            return $result;   
        }
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
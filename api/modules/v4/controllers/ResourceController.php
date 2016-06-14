<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\{Resource, ResourceClass,ResourceViewLog,ResourceStudyLog,Collection};
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\data\Pagination;

class ResourceController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\ResourceClass';

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
     * 药店列表
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function actionHospital()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset = $pagesize * ($page - 1); //计算记录偏移量
        $model = new ResourceClass();
        $data = $model::find()
            ->select('id,name')
            ->where(['parent' => 0, 'attr_type' => 0, 'status' => 1]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $rs = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        $array = array();
        $x = new ResourceClass();
        $y = new Resource();
        $z = new ResourceStudyLog();
        foreach ($rs as $resource) {

            $rsClass = $x::find()
                ->select('id')
                ->where(['parent' => $resource['id'], 'status'=>1])
                ->asArray()
                ->all();

            $time = $y::find()
                ->select('SUM(hour) AS hours')
                ->where(['status' => 1, 'publish_status' => 1, 'rid'=>array_column($rsClass,'id')])
                ->asArray()
                ->all();

            $class = $y::find()
                ->select('id')
                ->where(['status' => 1, 'publish_status' => 1, 'rid'=>array_column($rsClass,'id')])
                ->asArray()
                ->all();

            $hour = $time[0]['hours'];

            $study = $z::find()
                ->select('SUM(times) AS studyTime')
                ->where(['rid'=>array_column($class,'id')])
                ->asArray()
                ->all();

            $progress = $study[0]['studyTime']/1000/60/$hour;
            $progress = $progress ?? 0;

            $row = array('id' => $resource['id'], 'title' => $resource['name'], 'progress' => intval($progress));
            array_push($array, $row);
        }

        $result = ['code' => 200,'message'=>'药店列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$array]];
        return $result;
    }
    /**
     * 分类列表
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function actionChildhosp()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset = $pagesize * ($page - 1); //计算记录偏移量
        $rid = $this->params['rid'];

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=> $rid])
            ->orderBy(['publish_time'=>SORT_DESC]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $results = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        foreach ($results as &$val) {
            $val['labelName']='参与人数';
            $val['labelValue']=$val['views'];
            $val['type']= 'article';
            unset($val['views']);
        }

        $name = ResourceClass::find()
            ->where(['id' => $rid])
            ->one();

        $result = ['code' => 200,'message'=>$name->name,'data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$results]];
        return $result;

    }
    /**
     * 产品列表
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function actionProduct()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset = $pagesize * ($page - 1); //计算记录偏移量

        $resourceClass = new ResourceClass();
        $rsModel = $resourceClass::find()
            ->select('id')
            ->where(['parent' => 14, 'status'=>1])
            ->asArray()
            ->all();

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>array_column($rsModel,'id')])
            ->orderBy(['publish_time'=>SORT_DESC]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $results = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        foreach ($results as &$val) {
            $val['labelName']='参与人数';
            $val['labelValue']=$val['views'];
            $val['type']= 'article';
            unset($val['views']);
        }

        $result = ['code' => 200,'message'=>'产品列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$results]];
        return $result;
    }

    /**
     * 疾病列表
     * @version [2010-05-21]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function actionSickness()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset = $pagesize * ($page - 1); //计算记录偏移量

        $resourceClass = new ResourceClass();
        $rsModel = $resourceClass::find()
            ->select('id')
            ->where(['parent' => 15, 'status'=>1])
            ->asArray()
            ->all();

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>array_column($rsModel,'id')])
            ->orderBy(['publish_time'=>SORT_DESC]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $results = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        foreach ($results as &$val) {
            $val['labelName']='参与人数';
            $val['labelValue']=$val['views'];
            $val['type']= 'article';
            unset($val['views']);
        }

        $result = ['code' => 200,'message'=>'疾病列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$results]];
        return $result;
    }

    public function actionNav()
    {
        $pagesize = 10; // 默认每页记录数
        $page = $this->params['page'] ?? 1; // 当前页码
        $page = $page ? $page : 1;
        $offset = $pagesize * ($page - 1); //计算记录偏移量
        $rid = $this->params['rid'];

        $resourceClass = new ResourceClass();
        $data = $resourceClass::find()
            ->select('id,name')
            ->where(['parent' => $rid, 'status'=>1]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $rsModel = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        $array = array();
        $model = new Resource();
        foreach ($rsModel as $rs) {
            $resources = $model::find()
                ->select('SUM(hour) AS hours')
                ->where(['status' => 1, 'publish_status' => 1, 'rid' => $rs['id']])
                ->asArray()
                ->all();

            $hour = $resources[0]['hours'];

            $row = array('id' => $rs['id'], 'name' => $rs['name'], 'time' => $hour ?? 0);
            array_push($array, $row);
        }

        $result = ['code' => 200,'message'=>'栏目列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$array]];
        return $result;
        
    }
    /**
     * 嵌入式详情页
     * @author by lxhui
     * @version [2010-05-25]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function actionView()
    {
        $id=$this->params['id'];
        $uid=$this->uid;
        if(!$id)
        {
            $result = ['code' => -1,'message'=>'缺少ID!','data'=>null];
            return $result;
        }
        if(!Resource::findOne($id))
        {
            $result = ['code' => -1,'message'=>'资源不存在!','data'=>null];
            return $result;  
        }
        /* 记录该用户访问资源 */
        $viewModel = new ResourceViewLog();
        $res = $viewModel->find()->where(['uid'=>$this->uid,'rid'=>$id])->one();
        if(!$res)
        {
            $viewModel->uid= $this->uid;
            $viewModel->rid= $id;
            $viewModel->created_at= time();
            $viewModel->save();
           /* 更新资源访问量 */
            $model =  Resource::findOne($id);
            $model->views += 1;
            $model->save();
        }
       
        $where=['uid'=>$this->uid,'rid'=>$id,'type'=>1];
        $model = Collection::find($where)->where($where)->one();
        if($model)
            $iscollect = true;
        else
            $iscollect = false;
               
        $wapUrl = Yii::$app->params['wapUrl'].'/site/view/'.$id;
        $result = ['code' => 200,'message'=>'详情介绍','data'=>['wapUrl'=>$wapUrl,'iscollect'=>$iscollect]];
        return $result;
    }
    
    /**
     * 统计资源最后离开的时间
     * @author by lxhui
     * @version [2010-05-25]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */ 
    public function actionLeave()
    {
        $id=$this->params['id'];
        $times=$this->params['times']; //停留时间
        $uid=$this->uid;
        if(!$id)
        {
            $result = ['code' => -1,'message'=>'缺少ID!','data'=>null];
            return $result;
        }
        if(!$times)
        {
            $result = ['code' => -1,'message'=>'缺少访问时间!','data'=>null];
            return $result;
        }
        /* 记录该用户学习资源的记录 */
        $studyModel = new ResourceStudyLog();
        $studyModel->uid= $this->uid;
        $studyModel->rid= $id;
        $studyModel->times= $times;
        $studyModel->save();
        $result = ['code' => 200,'message'=>'离开资源','data'=>null];
        return $result;
    }

}
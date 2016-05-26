<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;
use api\common\models\{Resource, ResourceClass,ResourceViewLog,ResourceStudyLog};
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
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        $array = array();
        $progress = 5;
        foreach ($model as $resource) {
            $progress = $progress + 5;
            $row = array('id' => $resource['id'], 'title' => $resource['name'], 'progress' => $progress);
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

        $resourceClass = new ResourceClass();
        $rsModel = $resourceClass::find()
            ->select('id')
            ->where(['parent' => $rid, 'status'=>1])
            ->asArray()
            ->all();

//        print_r(array_column($rsModel,'id'));
        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>array_column($rsModel,'id')])
            ->orderBy(['publish_time'=>SORT_DESC]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

        $array = array();
        foreach ($model as $resource) {
            $row = array('id' => $resource['id'], 'title' => $resource['title'], 'views' => $resource['views'], 'imgurl' => $resource['imgurl'], 'type'=>"article");
            array_push($array, $row);
        }

        $name = $resourceClass::find()
            ->where(['id' => $rid])
            ->one();

        $result = ['code' => 200,'message'=>$name->name,'data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$array]];
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
            ->where(['name' => '产品', 'status'=>1])
            ->one();

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>$rsModel->id])
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
            ->where(['name' => '疾病', 'status'=>1])
            ->one();

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>$rsModel->id])
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
        $page = $this->params['page'] ?? 1; // 当前页码
        if($page<2)
            $isLastPage = false;
        else
            $isLastPage= true;
        if($page<2)
            $data=[
                ['id'=>'101','name'=> '第一阶段','times'=>'30分钟'],
                ['id'=>'102','name'=> '第二阶段','times'=>'20分钟'],
                ['id'=>'103','name'=> '第三阶段','times'=>'2小时'],
                ['id'=>'104','name'=> '第四阶段','times'=>'4天'],
                ['id'=>'211','name'=> '第五阶段','times'=>'30分钟'],
                ['id'=>'211','name'=> '第6阶段','times'=>'30分钟'],
                ['id'=>'213','name'=> '第7阶段','times'=>'30分钟'],
                ['id'=>'215','name'=> '第8阶段','times'=>'30分钟'],
                ['id'=>'567','name'=> '第9阶段','times'=>'30分钟'],
                ['id'=>'453','name'=> '第10阶段','times'=>'30分钟'],
                ];
        else
            $data=[
                ['id'=>'401','name'=> '第10阶段','times'=>'30分钟'],
                ['id'=>'402','name'=> '第11阶段','times'=>'20分钟'],
                ['id'=>'403','name'=> '第12阶段','times'=>'2小时'],
                ['id'=>'404','name'=> '第13阶段','times'=>'4天'],
                ['id'=>'411','name'=> '第14阶段','times'=>'30分钟'],
                ['id'=>'444','name'=> '第15阶段','times'=>'30分钟'],
            ];

        $result = ['code' => 200,'message'=>'栏目列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
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
               
        $wapUrl = 'http://wap.test.ohmate.com.cn/site/view/'.$id;
        $result = ['code' => 200,'message'=>'详情介绍','data'=>['wapUrl'=>$wapUrl]];
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
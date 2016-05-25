<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use common\models\ResourceClass;
use common\models\Resource;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\components\Helper;
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
//        $data=[
//            ['id'=>'101','title'=> '新手培训','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','progress'=>'50'],
//            ['id'=>'102','title'=> '店员培训','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','progress'=>'40'],
//            ['id'=>'103','title'=> '店长培训','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','progress'=>'70'],
//        ];
        $result = ['code' => 200,'message'=>'药店列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$array]];
        return $result;
    }

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

        $name = $resourceClass::find()
            ->where(['id' => $rid])
            ->one();

        $result = ['code' => 200,'message'=>$name->name,'data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$model]];
        return $result;

    }

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
            ->asArray()
            ->all();

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>array_column($rsModel,'id')])
            ->orderBy(['publish_time'=>SORT_DESC]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);

//        $page = $this->params['page'] ?? 1; // 当前页码
//        if($page<2)
//            $isLastPage = false;
//        else
//            $isLastPage= true;
//        if($page<2)
//            $data=[
//                ['id'=>'101','title'=> '普安药店员工收银服务指导说明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'2110'],
//                ['id'=>'102','title'=> '缺铁性贫血及推荐用药2','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'524546'],
//                ['id'=>'103','title'=> '缺铁性贫血及推荐用药3','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'45678'],
//                ['id'=>'104','title'=> '缺铁性贫血及推荐用药4','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5660',],
//                ['id'=>'211','title'=> '缺铁性贫血及推荐用药dd','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'67'],
//                ['id'=>'222','title'=> '缺铁性贫血及推荐用药rre','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//                ['id'=>'223','title'=> '缺铁性贫血及推荐用药78','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'22'],
//                ['id'=>'345','title'=> '缺铁性贫血及推荐用药55','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//                ['id'=>'345','title'=> '缺铁性贫血及推荐用药66','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
//                ['id'=>'543','title'=> '缺铁性贫血及推荐用药77','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//            ];
//        else
//            $data=[
//                ['id'=>'201','title'=> '普安药店员工收银服务22明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
//                ['id'=>'202','title'=> '缺铁性贫血及推荐用dsfds2','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5655'],
//                ['id'=>'203','title'=> '缺铁性贫血及推dadas药3','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'44443'],
//                ['id'=>'204','title'=> '缺铁性贫血及推fdsfd4','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'85569',],
//                ['id'=>'311','title'=> '缺铁性贫血及fdsfddd','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'455664'],
//                ['id'=>'322','title'=> '缺铁性贫血及fdfdsfds78','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'2222'],
//                ['id'=>'345','title'=> '测试啊as','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//                ['id'=>'445','title'=> '测试菜单是是','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'4455'],
//                ['id'=>'443','title'=> '123333324443哈哈哈','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'43'],
//            ];

        $result = ['code' => 200,'message'=>'产品列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$model]];
        return $result;
    }


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
            ->asArray()
            ->all();

        $model = new Resource();
        $data = $model::find()
            ->select('id,title,views,imgurl')
            ->where(['status'=>1,'publish_status'=>1,'rid'=>array_column($rsModel,'id')])
            ->orderBy(['publish_time'=>SORT_DESC]);

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pagesize]);
        $model = $data->offset($offset)->limit($pages->limit)->asArray()->all();
        $total_page = ceil($data->count() / $pagesize);
//        $page = $this->params['page'] ?? 1; // 当前页码
//        if($page<2)
//            $isLastPage = false;
//        else
//            $isLastPage= true;
//        if($page<2)
//            $data=[
//                ['id'=>'101','title'=> '尿毒症的危险','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'2110'],
//                ['id'=>'102','title'=> '尿毒症的危险22','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'524546'],
//                ['id'=>'103','title'=> '尿毒症的危险333','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'45678'],
//                ['id'=>'104','title'=> '尿毒症的危险55','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5660',],
//                ['id'=>'211','title'=> '尿毒症的危险777','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'67'],
//                ['id'=>'222','title'=> '尿毒症的危险888','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//                ['id'=>'223','title'=> '尿毒症的危险883','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'22'],
//                ['id'=>'345','title'=> '尿毒症的危险909','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//                ['id'=>'345','title'=> '尿毒症的危险12323','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
//                ['id'=>'543','title'=> '尿毒症的危险56433','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
//            ];
//        else
//            $data=[
//                ['id'=>'201','title'=> '普安药店员工收银服务22明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
//                ['id'=>'202','title'=> '尿毒症的危险56433','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5655'],
//                ['id'=>'203','title'=> '尿毒症的危险5643322','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'44443'],
//                ['id'=>'204','title'=> '尿毒症的危险4444','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'85569',],
//                ['id'=>'311','title'=> '尿毒症的危险32221','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'455664'],
//            ];
//
//        $result = ['code' => 200,'message'=>'疾病列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
        $result = ['code' => 200,'message'=>'疾病列表','data'=>['isLastPage'=>$page >= $total_page ? true : false ,'list'=>$model]];
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

    public function actionView()
    {
        $id=$this->params['id'];
        if(!$id)
        {
            $result = ['code' => -1,'message'=>'缺少ID!','data'=>null];
            return $result;
        }
        $wapUrl = 'http://wap.test.ohmate.com.cn/site/view/'.$id;
        $result = ['code' => 200,'message'=>'详情介绍','data'=>['wapUrl'=>$wapUrl,'list'=>null]];
        return $result;
    }

}
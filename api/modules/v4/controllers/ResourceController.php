<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use common\models\Region;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\components\Helper;
use yii\base\InvalidConfigException;
class ResourceController extends \api\common\controllers\Controller
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

    public function actionHospital()
    {
        $data=[
            ['id'=>'101','title'=> '新手培训','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','progress'=>'50'],
            ['id'=>'102','title'=> '店员培训','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','progress'=>'40'],
            ['id'=>'103','title'=> '店长培训','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','progress'=>'70'],
        ];
        $result = ['code' => 200,'message'=>'药店列表','data'=>['isLastPage'=>true ,'list'=>$data]];
        return $result;
    }

    public function actionProduct()
    {
        $page = $this->params['page'] ?? 1; // 当前页码
        if($page<2)
            $isLastPage = false;
        else
            $isLastPage= true;
        if($page<2)
            $data=[
                ['id'=>'101','title'=> '普安药店员工收银服务指导说明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'2110'],
                ['id'=>'102','title'=> '缺铁性贫血及推荐用药2','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'524546'],
                ['id'=>'103','title'=> '缺铁性贫血及推荐用药3','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'45678'],
                ['id'=>'104','title'=> '缺铁性贫血及推荐用药4','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5660',],
                ['id'=>'211','title'=> '缺铁性贫血及推荐用药dd','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'67'],
                ['id'=>'222','title'=> '缺铁性贫血及推荐用药rre','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
                ['id'=>'223','title'=> '缺铁性贫血及推荐用药78','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'22'],
                ['id'=>'345','title'=> '缺铁性贫血及推荐用药55','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
                ['id'=>'345','title'=> '缺铁性贫血及推荐用药66','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
                ['id'=>'543','title'=> '缺铁性贫血及推荐用药77','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
            ];
        else
            $data=[
                ['id'=>'201','title'=> '普安药店员工收银服务22明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
                ['id'=>'202','title'=> '缺铁性贫血及推荐用dsfds2','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5655'],
                ['id'=>'203','title'=> '缺铁性贫血及推dadas药3','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'44443'],
                ['id'=>'204','title'=> '缺铁性贫血及推fdsfd4','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'85569',],
                ['id'=>'311','title'=> '缺铁性贫血及fdsfddd','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'455664'],
                ['id'=>'322','title'=> '缺铁性贫血及fdfdsfds78','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'2222'],
                ['id'=>'345','title'=> '测试啊as','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
                ['id'=>'445','title'=> '测试菜单是是','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'4455'],
                ['id'=>'443','title'=> '123333324443哈哈哈','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'43'],
            ];

        $result = ['code' => 200,'message'=>'产品列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
        return $result;
    }


    public function actionSickness()
    {
        $page = $this->params['page'] ?? 1; // 当前页码
        if($page<2)
            $isLastPage = false;
        else
            $isLastPage= true;
        if($page<2)
            $data=[
                ['id'=>'101','title'=> '尿毒症的危险','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'2110'],
                ['id'=>'102','title'=> '尿毒症的危险22','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'524546'],
                ['id'=>'103','title'=> '尿毒症的危险333','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'45678'],
                ['id'=>'104','title'=> '尿毒症的危险55','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5660',],
                ['id'=>'211','title'=> '尿毒症的危险777','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'67'],
                ['id'=>'222','title'=> '尿毒症的危险888','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
                ['id'=>'223','title'=> '尿毒症的危险883','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'22'],
                ['id'=>'345','title'=> '尿毒症的危险909','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
                ['id'=>'345','title'=> '尿毒症的危险12323','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
                ['id'=>'543','title'=> '尿毒症的危险56433','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110'],
            ];
        else
            $data=[
                ['id'=>'201','title'=> '普安药店员工收银服务22明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'34'],
                ['id'=>'202','title'=> '尿毒症的危险56433','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'5655'],
                ['id'=>'203','title'=> '尿毒症的危险5643322','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'44443'],
                ['id'=>'204','title'=> '尿毒症的危险4444','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'85569',],
                ['id'=>'311','title'=> '尿毒症的危险32221','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'455664'],
            ];

        $result = ['code' => 200,'message'=>'疾病列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
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
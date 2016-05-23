<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\components\Helper;
use yii\base\InvalidConfigException;
class SearchController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Resource';

    protected function verbs(){
        return [
            'index'=>['POST'],
          
        ];
    }
    public function actionIndex()
    {
        $keyword= $this->params['keyword'] ?? ''; // 当前页码
        if(!$keyword)
        {
            $result = ['code' => -1,'message'=>'关键词不能为空!','data'=>null];
            return $result;  
        }
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

        $result = ['code' => 200,'message'=>'搜索列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
        return $result;
    }

    public function actionRemind()
    {
        $keyword= $this->params['keyword'] ?? ''; // 当前页码
        if(!$keyword)
        {
            $result = ['code' => -1,'message'=>'关键词不能为空!','data'=>null];
            return $result;  
        }
        $data=[
           ['keyword'=>'甲状腺'],
           ['keyword'=>'糖凝胶囊'],
           ['keyword'=>'胰岛素'],
          ];
       
        $result = ['code' => 200,'message'=>'关键词提醒','data'=>$data];
        return $result;
    }

}
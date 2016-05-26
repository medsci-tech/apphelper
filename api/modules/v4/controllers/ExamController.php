<?php
/**
 * Created by PhpStorm.
 * User: lxhui
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
        $page = $this->params['page'] ?? 1; // 当前页码
        if($page<2)
            $isLastPage = false;
        else
            $isLastPage= true;
        if($page<2)
            $data=[
                ['id'=>'101','title'=> '大学语文','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>'进行中','labelName'=>'历史最佳','labelValue'=>'A'],
                ['id'=>'102','title'=> '大学数学','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>'进行中','labelName'=>'历史最佳','labelValue'=>'C'],
                ['id'=>'103','title'=> '大学微积分','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>null,'labelName'=>'国际最佳','labelValue'=>'D'],
                ['id'=>'104','title'=> '大学语文大学英语','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>null,'labelName'=>'新人榜','labelValue'=>'F'],
                ['id'=>'211','title'=> '马克思哲学','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>'已完成','labelName'=>'琅琊榜','labelValue'=>'G'],
                ['id'=>'222','title'=> '马克思哲学222','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>null,'labelName'=>'参与人数','labelValue'=>'G'],
                ['id'=>'345','title'=> '缺铁性贫血及','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>null,'labelName'=>null,'labelValue'=>null],
                ['id'=>'345','title'=> '缺铁性贫血及','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>null,'labelName'=>null,'labelValue'=>null],
                ['id'=>'543','title'=> '缺铁性贫血及','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'12','status'=>null,'labelName'=>null,'labelValue'=>null],
            ];
        else
            $data=[
                ['id'=>'201','title'=> '大学英语','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'F'],
                ['id'=>'202','title'=> '大学英语2','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'P'],
                ['id'=>'203','title'=> '大学英语3','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'U'],
                ['id'=>'204','title'=> '大学英语4','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'P',],
                ['id'=>'311','title'=> '大学英语5','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'P'],
                ['id'=>'322','title'=> '大学英语6','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'P'],
                ['id'=>'345','title'=> '大学英语','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'P'],
                ['id'=>'445','title'=> '大学英语7','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'琅琊榜','labelValue'=>'P'],
                ['id'=>'443','title'=> '123333324443哈哈哈','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','total'=>'15','status'=>null,'labelName'=>'国内最佳','labelValue'=>'P'],
            ];

        $result = ['code' => 200,'message'=>'试卷列表','data'=>['isLastPage'=>$isLastPage ,'list'=>$data]];
        return $result;
    }
  

}
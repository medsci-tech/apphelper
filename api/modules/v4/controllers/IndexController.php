<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use common\models\AD;
use common\models\Region;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Response;
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
    public function actionAd()
    {
        $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][1]); //获取缓存
        if(!$data)
        {
            /* 查询数据库 */
            $model = new AD();
            $data = $model::find()
                ->select('id,title,linkurl,imgurl')
                ->where(['status' => 1])
                ->all();
            $result = ['code' => 200, 'message' => '轮播图', 'data' => $data];
            Yii::$app->cache->set(Yii::$app->params['redisKey'][1],json_encode($data),2592000);
        }
        else { // 存在缓存值
            $data = json_decode(Yii::$app->cache->get(Yii::$app->params['redisKey'][1]), true);
            $result = ['code' => 200, 'message' => '轮播图', 'data' => $data];
        }
        return $result;
    }
    public function actionIndex()
    {
        $data=[
            ['id'=>'101','classname'=> '疾病','title'=> '普安药店员工收银服务指导说明','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110','type'=> 'article'],
            ['id'=>'102','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药2','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'题目总数','labelValue'=>'56','type'=> 'exam'],
            ['id'=>'103','classname'=> '产品','title'=> '缺铁性贫血及推荐用药3','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'99','type'=> 'article'],
            ['id'=>'104','classname'=> '疾病','title'=> '缺铁性贫血及推荐用药4','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'89','type'=> 'article'],
            ['id'=>'211','classname'=> '疾病','title'=> '缺铁性贫血及推荐用药dd','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'67','type'=> 'article'],
            ['id'=>'222','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药rre','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110','type'=> 'exam'],
            ['id'=>'223','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药78','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'题目总数','labelValue'=>'22','type'=> 'article'],
            ['id'=>'345','classname'=> '药店','title'=> '缺铁性贫血及推荐用药55','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110','type'=> 'article'],
            ['id'=>'345','classname'=> '考卷','title'=> '缺铁性贫血及推荐用药66','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'题目总数','labelValue'=>'34','type'=> 'exam'],
            ['id'=>'543','classname'=> '疾病','title'=> '缺铁性贫血及推荐用药77','imgurl'=>'https://ss0.baidu.com/73F1bjeh1BF3odCf/it/u=474172776,701640655&fm=96&s=1728FE05065359C6069C39F1030050B0','labelName'=>'参与人数','labelValue'=>'110','type'=> 'article'],
        ];
        $result = ['code' => 200,'message'=>'推荐列表','data'=>['isLastPage'=>true,'list'=>$data]];
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
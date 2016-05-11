<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use backend\models\search\Member;
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
            'rank'=>['POST'],
            'reg'=>['POST'],
            'nickname'=>['POST'],
            'username'=>['POST'],
            'realname'=>['POST'],
        ];
    }
    public function actionIndex()
    {
        $topStories = Article::find()->orderBy(['view' => SORT_DESC])->limit(5)->asArray()->all();
        $stories = Article::find()->orderBy(['created_at' => SORT_DESC, 'title' => SORT_ASC])->limit(10)->asArray()->all();
        return [
            'date' => date('Ymd'),
            'stories' => $stories,
            'top_stories' => $topStories
        ];
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
        $p ='湖北';        $c ='武汉';
        $regModel = new Region();
        $res = $regModel->getByName($p,$c);
        print_r($res);exit;

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

    public function actionDelete($id)
    {
        echo(110);
    }

}
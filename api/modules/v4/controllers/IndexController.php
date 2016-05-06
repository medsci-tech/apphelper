<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace api\modules\v4\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\Response;
use yii\base\InvalidConfigException;
class IndexController extends \api\common\controllers\Controller
{
    public $modelClass = 'api\common\models\Member';
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;

    }

    protected function verbs(){
        return [
            'index'=>['GET','POST'],
            'rank'=>['POST'],
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
        //$data = Yii::$app->params('member').rank;
        print_r($p);exit;
        return [
            'date' => date('Ymd'),
            'stories' => $stories,
            'top_stories' => $topStories
        ];
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
            $result = ['code' => '-1','message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => '200','message'=>'设置成功','data'=>null];
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
            $result = ['code' => '-1','message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => '200','message'=>'设置成功','data'=>null];
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
            $result = ['code' => '-1','message'=>$message,'data'=>null];
        }
        else
            $result = ['code' => '200','message'=>'设置成功','data'=>null];
        return $result;
    }

    public function actionList()
    { echo'test141';exit;
        $query = Article::find();
        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'title' => SORT_ASC,
                ]
            ],
        ]);
        return [
            'date' => date('Ymd'),
            'stories' => $provider->getModels(),
        ];
    }
    public function actionView($id = 0)
    {
        $article = Article::find()->where(['id' => $id])->with('data')->asArray()->one();
        return $article;
    }
    public function actionDelete($id)
    {
        echo(110);
    }
    private function _getStatusCodeMessage($status)
    {
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }
}
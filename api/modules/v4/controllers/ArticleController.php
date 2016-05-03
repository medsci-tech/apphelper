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
use api\modules\v4\models\Article;
use yii\base\InvalidConfigException;
class ArticleController extends Controller
{
    public $modelClass = 'api\modules\v4\models\article';//Yii::$app->getRequest()->getBodyParams()['newsItem'];
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;

    }
    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
    protected function verbs(){
        return [
            'index'=>['GET','POST'],
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
    public function actionTest()
    {
        print_r($_POST);
        echo'12t';exit;
    }
    public function actionCreate()
    {
        //echo'test11';exit;
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
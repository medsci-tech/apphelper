<?php
namespace frontend\controllers;

use api\common\models\Resource_view_log;
use Yii;
use common\models\Resource;
use api\common\models\Resourceviewlog;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout=false;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $m = Resource::findOne($id);
        $log = Resourceviewlog::find()->select(['uid','rid'])->all();

        Resource::updateAll(['views'=>$m->views+1],'id=:id',array(':id'=>$id));//阅读量+1
        $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][2].$id); //获取缓存
        $data  = json_decode($data,true);
        if(!$data)
        {
            /* 查询数据库 */
            $data = Resource::find()
                ->select(['title','content','views'])
                ->where(['id' => $id])
                ->asArray()
                ->one();
            Yii::$app->cache->set(json_encode(Yii::$app->params['redisKey'][2].$id),2592000);
        }

        return $this->render('view', [
            'data' => $data,
        ]);
    }

}

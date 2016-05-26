<?php
namespace frontend\controllers;

use Yii;
use common\models\Resource;
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
     * 资源详情页
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][2].$id); //获取缓存
        $data  = json_decode($data,true);
        if(!$data)
        {
            /* 查询数据库 */
            $data = Resource::find()
                ->select(['title','content','views','publish_time'])
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

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
        //Yii::$app->cache->flush(); 
        $data = Yii::$app->cache->get(Yii::$app->params['redisKey'][2].$id); //获取缓存
        $data  = json_decode($data,true);
        if(!$data) 
        {
            /* 查询数据库 */
            $data = Resource::find()
                ->select(['title','content','views','publish_time','ppt_imgurl','videourl'])
                ->where(['id' => $id])
                ->asArray()
                ->one();
            Yii::$app->cache->set(json_encode(Yii::$app->params['redisKey'][2].$id),2592000);
        }
        if($data['ppt_imgurl'])
            $data['ppt_imgurl'] = unserialize($data['ppt_imgurl']);

		is_array($data['ppt_imgurl'])?null:$data['ppt_imgurl']=[];

        return $this->render('view', [
            'data' => $data,
        ]);
    }

    /**
     * 注册协议
     *
     * @return mixed
     */
    public function actionProtocol()
    {
      return $this->render('protocol', [

        ]);  
      
    }


    /**
     * app下载功能
     * @author by lxhui
     * @version [2010-07-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function actionDown()
    {
        return $this->render('down', [

        ]);
    }

}

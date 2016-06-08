<?php
namespace frontend\controllers;
use Yii;
use common\models\Message;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class MessageController extends Controller
{
    public $layout=false;
    /**
     * 资源详情页
     *
     * @return mixed
     */
    public function actionView($id)
    {
        /* 查询数据库 */
        $data = Message::find()
            ->select(['title','content'])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        return $this->render('view', [
            'data' => $data,
        ]);
    }

}

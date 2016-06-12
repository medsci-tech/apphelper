<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 11:36
 */

namespace backend\controllers;

use common\models\Member;
use common\models\Message;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\search\Message as MessageSearch;


class MessageController extends BackendController {

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Message models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search($appYii->request->queryParams);

        return $this->render('index', [
            'model' => new Message(),
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $params = Yii::$app->request->post();

        if(1 == $params['push_type']) {
            $message = $params['Message'];
            if ($message['id']) {
                $model = $this->findModel($message['id']);
                $model->title = $message['title'];
                $model->content = $message['content'];
                $model->link_url = $message['link_url'];
            } else {
                $model = new Message();

            }
        }else

        if ('send' == $params['type']) {

        }

        if ('save' == $params['type']) {

        }

        return $this->redirect(['index']);
    }

    public function actionMember()
    {
        return $this->render('member');
    }

    public function actionUser()
    {
        $params = Yii::$app->request->get();

        if($params['phone']){
            print_r($params['phone']);
//            $str = str_replace(array("\r\n", "\r", "\n"), "", $params['phone']);
            $phones = preg_split('/\r\n/', $params['phone']);
            print_r($phones);
            $array = array();
            foreach($phones as $phone) {
                $user = Member::find()
                    ->select('id')
                    ->where(['username' => $phone])
                    ->one();
                $row = array('id' =>$user->id);
                array_push($array, $row);
            }

            Yii::$app->cache->set('MessageUser',json_encode($array));
//            print_r($array);
        }
    }

    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
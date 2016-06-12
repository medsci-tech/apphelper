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
use common\components\Getui;
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

        if ('add' == $params['type']) {

            $message = $params['Message'];
            if (1 == $params['push_type']) {
                $userList = Array();
                $array = array();
                foreach($userList as $user){
                    $model = new Message();
                    $model->title = $message['title'];
                    $model->content = $message['content'];
                    $model->link_url = $message['link_url'];
                    $model->push_type = $params['push_type'];
                    $model->isread = 0;
                    $model->touid = $user;
                    $model->create_at = time();
                    $model->status = 0;
                    $model->save(false);
                    $row = array('id' =>$model->id);
                    array_push($array, $row);
                }
            } else {
                $model = new Message();
                $model->title = $message['title'];
                $model->content = $message['content'];
                $model->link_url = $message['link_url'];
                $model->push_type = $params['push_type'];
                $model->isread = 0;
                $model->create_at = time();
                $model->status = 0;
                $model->save(false);
                $id = $model->id;
            }

            if ('send' == $params['type']) {
                $push = new Getui();
                $status = 0;
                if (1 == $params['push_type']) {
                    $push->pushMessageToApp();
                    $model = $this->findModel($id);
                    $model->status = 1;
                    $model->send_at = time();
                    $model->save(false);
                } else {

                    $push->pushSingle($message['title'],$message['content'],$array);
                    foreach($array as $user){
                        $model = $this->findModel($user);
                        $model->status = 1;
                        $model->send_at = time();
                        $model->save(false);
                    }
                }
            }


//            if ('save' == $params['type']) {
//                $status = 0;
//            }
        }

        if ('edit' == $params['type']) {

            $message = $params['Message'];
            if ($message['id']) {
                $model = $this->findModel($message['id']);
                $model->title = $message['title'];
                $model->content = $message['content'];
                $model->link_url = $message['link_url'];
                $model->save(false);
            }

            if ('send' == $params['type']) {
                $push = new Getui();
                if (1 == $params['push_type']) {
                    $push->pushMessageToApp();
                } else {
                    $array = array();
                    $row = array('id' =>$model->id);
                    array_push($array, $row);
                    $push->pushSingle($message['title'],$message['content'],$array);
                }
                $model->status = $status;
                $model->send_at = time();
                $model->save(false);
            }

//            if ('save' == $params['type']) {
//
//            }
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
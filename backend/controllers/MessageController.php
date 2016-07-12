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

        if ('add' == $params['post_type']) {

            $message = $params['Message'];
            if (1 == $params['push_type']) {
                $model = new Message();
                $model->title = $message['title'];
                $model->content = $message['content'];
                $model->link_url = $message['link_url'];
                $model->push_type = $params['push_type'];
                $model->isread = 0;
                $model->created_at = time();
                $model->status = 0;
                $model->uid = Yii::$app->user->id;
                $model->save(false);
                $id = $model->id;
            } else {
                $data = Yii::$app->cache->get('MessageUser');
                $userList = json_decode($data, true);
                $array = array();
//                $touids = array();
                is_array($userList)? null : $userList=[];
                foreach($userList as $user){
                    $model = new Message();
                    $model->title = $message['title'];
                    $model->content = $message['content'];
                    $model->link_url = $message['link_url'];
                    $model->push_type = $params['push_type'];
                    $model->isread = 0;
                    $model->touid = $user['id'];
                    $model->created_at = time();
                    $model->status = 0;
                    $model->uid = Yii::$app->user->id;
                    $model->save(false);
                    $row = array('id' =>$model->id);
                    array_push($array, $row);
//                    array_push($touids, $user['id']);
                }
                Yii::$app->cache->delete('MessageUser');
            }

            if ('send' == $params['type']) {
                $push = new Getui();
                if (1 == $params['push_type']) {
                    $push->pushMessageToApp($message['title'],$message['content']);
                    $model = $this->findModel($id);
                    $model->status = 1;
                    $model->send_at = time();
                    $model->save(false);
                } else {

                    $push->pushSingle($message['title'],$message['content'], $userList);
                    foreach($array as $ms){
                        $model = $this->findModel($ms);
                        $model->status = 1;
                        $model->send_at = time();
                        $model->save(false);
                    }
                }
            }
//            return $this->redirect(['message/index']);
        }

        if ('edit' == $params['post_type']) {

            $message = $params['Message'];
            if ($message['id']) {
                $model = $this->findModel($message['id']);
                $model->title = $message['title'];
                $model->content = $message['content'];
                $model->link_url = $message['link_url'];
                $model->uid = Yii::$app->user->id;
                $model->save(false);
            }

            if ('send' == $params['type']) {
                $push = new Getui();
                if (1 == $params['push_type']) {
                    $push->pushMessageToApp($message['title'],$message['content']);
                } else {
                    $array = array();
                    $row = array('id' =>$model->touid);
                    array_push($array, $row);
                    $push->pushSingle($message['title'],$message['content'],$array);
                }
                $model->status = 1;
                $model->send_at = time();
                $model->save(false);
            }
//            return $this->redirect(['message/index']);
        }

        return $this->redirect(['message/index']);
    }

    public function actionMember()
    {
        return $this->render('member');
    }

    public function actionUser()
    {
        $params = Yii::$app->request->post();

        Yii::$app->cache->delete('MessageUser');
        if($params['phone']){
//            print_r($params['phone']);
//            $str = str_replace(array("\r\n", "\r", "\n"), "", $params['phone']);
            $phones = preg_split('/\r\n/', $params['phone']);
//            print_r($phones);
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
//            print_r(Yii::$app->cache->get('MessageUser'));
            $return = ['code'=>200,'msg'=>'提交成功','data'=>json_encode($array)];
        }
        else{
            $return = ['code'=>802,'msg'=>'提交失败','data'=>''];
        }

        $this->ajaxReturn($return);
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
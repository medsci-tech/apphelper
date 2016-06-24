<?php
/**
 * Created by PhpStorm.
 * User: mime
 * Date: 2016/6/20
 * Time: 13:29
 */
namespace backend\controllers;

use common\models\Video;
use Yii;
use backend\models\search\Video as VideoSearch;


class VideoController extends BackendController
{
    
    public function actionIndex()
    {
        $appYii = Yii::$app;
        $search = new VideoSearch();
        $dataProvider = $search->search($appYii->request->queryParams);
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 保存公共方法
     * @author zhaiyu
     * @return array
     */
    public function actionForm()
    {
        $appYii = Yii::$app;
        $post = $appYii->request->post();

        if(isset($post['imgurl']) && isset($post['name'])){
            $transaction = $appYii->db->beginTransaction(); //开启事务
            try {
                $data = [];
                foreach ($post['imgurl'] as $key => $val){
                    $data['url'] = $val;
                    $data['name'] = $post['name'][$key];
                    $data['type'] = $this->getFileInfo($val);
                    $data['created_at'] = time();
                    $appYii->db->createCommand()->insert('{{%video}}',$data)->execute();
                }
                $transaction->commit(); // 两条sql均执行成功，则提交
                $return = ['code' => 200,'msg' => 'success'];
            } catch (\Exception $e) {
                $transaction->rollBack(); // 事务执行失败，则回滚
                $return = ['code' => 802,'msg' => '远程保存失败'];
            }
        }else{
            $return = ['code' => 801, 'msg' => '数据有误，请重试', 'data' => ''];
        }
        $this->ajaxReturn($return);
    }

    public function actionCreate(){
        return $this->render('create', [
            'ajaxUrl' => 'form',
            'ajaxType' => 'post',
            'ajaxLocation' => 'index',
        ]);
    }
    public function actionView($id){
        $model = Video::findOne($id);
//       var_dump($model);
        return $this->render('view', [
            'modelData' => $model,
        ]);
    }
}
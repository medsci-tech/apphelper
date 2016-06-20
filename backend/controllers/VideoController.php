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
        $post = Yii::$app->request->post();
        $model = new Video();
        $model->load($post);
        $isValid = $model->validate();
        if ($isValid) {
            $model->created_at = time();
            $result = $model->save(false);
            if ($result) {
                $return = ['code' => 200, 'msg' => '', 'data' => ''];
            } else {
                $return = ['code' => 801, 'msg' => '服务端操作失败', 'data' => ''];
            }
        }else{
            $return = ['code'=>802,'msg'=>'数据有误','data'=>''];
        }
        $this->ajaxReturn($return);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

use backend\models\search\Comment as CommentSearch;
use common\models\Comment;
use common\models\Member;
use yii\data\ActiveDataProvider;
use Yii;
class CommentController extends BackendController
{

    /**
     * 评论资源详情/题目详情列表
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @return string
     */
    public function actionIndex()
    {
        /*条件查询*/
        $params = [
            'title' => '',
            'type' => 'resource',
        ];
        $search = new CommentSearch();
        $dataProvider = $search->search($params);

        return $this->render('resource-index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 资源一级评论列表
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @param $id
     * @return string
     */
    public function actionYi($id)
    {
        if(empty($id)){
            $this->redirect('index');
        }
        $where = [
            'cid' => 0,
            'rid' => $id,
        ];
        $model = new CommentSearch();
        $dataProvider = $model->searchYi($where);
        return $this->render('comment', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 资源二级评论列表
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160613
     * @param $rid
     * @param cid
     * @return string
     */
    public function actionEr($rid, $cid)
    {
        if(empty($rid || $cid)){
            $this->redirect('index');
        }
        $where = [
            'cid' => $cid,
            'rid' => $rid,
        ];
        $model = new CommentSearch();
        $dataProvider = $model->searchYi($where);
        return $this->render('info', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 培训更改状态
     * @author zhaiyu
     * @startDate 20160613
     * @upDate 20160613
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $appYii = Yii::$app;
        $params = $appYii->request->post();
        $id = $appYii->request->get('id');
        if(isset($params['selection'])) {
            $model = new Comment();
            if ('del' == $params['type']) {
                /*删除*/
                foreach ($params['selection'] as $key => $val) {
                    $this->findModel($val)->delete();
                }
            }elseif ('enable' == $params['type']) {
                /*启用*/
                $model->saveData(['id' => $params['selection']], ['status' => 1]);
            } elseif ('disable' == $params['type']) {
                /*禁用*/
                $model->saveData(['id' => $params['selection']], ['status' => 0]);
            }
        }elseif($id){
            $this->findModel($id)->delete();
        }
        return $this->redirect($appYii->request->referrer);
    }

    /**
     * @param $id
     * @return null|static
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            return false;
        }
    }
}
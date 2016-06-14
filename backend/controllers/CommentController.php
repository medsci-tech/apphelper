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
use common\models\Resource;
use common\models\Exercise;
use common\models\ResourceClass;
use Yii;
use yii\web\Cookie;
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
        $appYii = Yii::$app;
        $type = $appYii->request->queryParams['type'] ?? '';
//        var_dump($appYii->request->url);exit;
        /*条件查询*/
        if($type == 'exercise'){
            $search = new Exercise();
            $view = 'exercise';
        }else{
            $search = new Resource();
            $view = 'resource';
        }
        $dataProvider = (new CommentSearch())->search($appYii->request->queryParams);

        //资源种类
        $cateList = [];
        $resourceClassModel = new ResourceClass();
        $cateListArr = $resourceClassModel->getDataForWhere(['parent' => 0]);
        if($cateListArr){
            foreach ($cateListArr as $key => $val){
                $cateList[$val['id']] = $val['name'];
            }
        }
        $cateList['exercise'] = '试题';
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'comment-index-html',
            'value' => $appYii->request->url,
        ]));
        return $this->render($view . '-index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
            'cateList' => $cateList,
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
        $dataProvider = $model->searchComment($where);
        //记录本页面URL
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'comment-yi-html',
            'value' => Yii::$app->request->url,
        ]));
        $referrerUrl = Yii::$app->request->cookies->getValue('comment-index-html');
        return $this->render('comment', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl,
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
        $dataProvider = $model->searchComment($where);
        $referrerUrl = Yii::$app->request->cookies->getValue('comment-yi-html');
        return $this->render('info', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
            'referrerUrl' => $referrerUrl,
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
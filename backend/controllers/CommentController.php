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
        $search = new CommentSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        return $this->render('resource-index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 某个资源一级评论列表
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
        $model = Comment::find();
        $model->andFilterWhere($where);
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find(),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
//        $dataProvider = $search::find()->where($where)->sql;
        return $this->render('comment', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 某个资源一级评论列表
     * @author zhaiyu
     * @startDate 20160612
     * @upDate 20160612
     * @param $id
     * @return string
     */
    public function actionEr($id)
    {
        if(empty($id)){
            $this->redirect('index');
        }
        $search = new Comment();
        $where = [
            'cid' => 0,
            'rid' => $id,
        ];
        $dataProvider = $search::find()->where($where);
        return $this->render('comment', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

}
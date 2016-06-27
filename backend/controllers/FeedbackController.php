<?php
/**
 * Created by PhpStorm.
 * User: lxhui
 * Date: 16-1-28
 * Time: 下午6:40
 */

namespace backend\controllers;

use backend\models\search\Feedback as FeedbackSearch;
use common\models\Member;
use common\models\Feedback;
use Yii;
use yii\data\ActiveDataProvider;
class FeedbackController extends BackendController
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
        $queryParams = Yii::$app->request->queryParams;
//        $startTime = $queryParams['startTime'] ?? '';
//        $endTime = $queryParams['endTime'] ?? '';
        $search = new Feedback();
        $dataProvider = $this->search($queryParams);
        return $this->render('index', [
            'searchModel' => $search,
            'dataProvider' => $dataProvider,
        ]);
    }

    //搜索
    public function search($params)
    {
        $model = new Feedback();
        $query = $model::find();
        $startTime = $params['startTime'] ?? '';
        $endTime = $params['endTime'] ?? '';
        if($startTime){
            $query->andFilterWhere(['>=', 'created_at', strtotime($startTime)]);
        }
        if($endTime){
            $query->andFilterWhere(['<=', 'created_at', strtotime($endTime)]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Resource as ResourceModel;
use common\models\Exercise as ExerciseModel;
use common\models\Comment as CommentModel;

class Comment extends ResourceModel
{

    public function search($params)
    {
        
        if(isset($params['type'])){
            if($params['type'] == 'exercise'){
                $query = Resource::find();
                $query->andFilterWhere(['like', 'question', $params['title']]);
            }else{
                $query = Exercise::find();
                $query->andFilterWhere(['like', 'title', $params['title']]);
//                if(){
//
//                }
            }
        }else{
            $query = Resource::find();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * 评论详情搜索
     * @param $where
     * @return ActiveDataProvider
     */
    public function searchComment($where)
    {
        $model = $this::find();
        $model->andFilterWhere($where);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }
}
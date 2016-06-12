<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\data\ActiveDataProvider;
use common\models\Resource as ResourceModel;
use common\models\Exercise as ExerciseModel;
use common\models\Comment as CommentModel;

class Comment extends CommentModel
{
    
    public function search($params)
    {
        if($params['type'] == 'exam'){
            $query = ExerciseModel::find();
            $query->andFilterWhere(['like', 'question', $params['title']]);
        }else{
            $query = ResourceModel::find();
            $query->andFilterWhere(['like', 'title', $params['title']]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

    public function searchYi($where)
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
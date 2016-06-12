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

class Comment extends ResourceModel
{
//    public function rules()
//    {
//        return [
//            [['title'], 'string'],
//        ];
//    }

    public function search($params)
    {
//        $this->load($params);
////        if('resource' == $params['type']){
//        if(1){
//            $query = ResourceModel::find();
//            $query->andFilterWhere(['like', 'title', $this->title]);
//        }else{
//            $query = ExerciseModel::find();
//            $query->andFilterWhere(['like', 'question', $this->title]);
//        }
        $dataProvider = new ActiveDataProvider([
            'query' => ResourceModel::find(),
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }
}
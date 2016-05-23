<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\data\ActiveDataProvider;
use common\models\Exam as ExamModel;

class Exam extends ExamModel
{
    public function rules()
    {
        return [
            [['name'], 'string'],
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $query = ExamModel::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;
    }
}
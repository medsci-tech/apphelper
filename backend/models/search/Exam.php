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
use common\models\ExamClass;

class Exam extends ExamModel
{
    public function rules()
    {
        return [
            [['category'], 'integer'],
            [['question'], 'string'],
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $query = ExamModel::find()->orderBy('id desc');
        $query->andFilterWhere(['like', 'question', $this->question]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
}
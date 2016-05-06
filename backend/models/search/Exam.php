<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\data\ActiveDataProvider;
use common\models\Exercise as ExerciseModel;
use common\models\ExamClass;

class Exercise extends ExerciseModel
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
        $category = [];
        $examClassFind = (new ExamClass())->getDataForWhere(['like', 'path',',' . $this->category . ',']);
        foreach ($examClassFind as $val){
            $category[] = $val['id'];
        }
        $query = ExerciseModel::find()->orderBy('id desc');
        $query->andFilterWhere(['category'=> $category]);
        $query->andFilterWhere(['like', 'question', $this->question]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/20
 * Time: 14:58
 */

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ClassQuestion as ClassQuestionModel;

class ClassQuestion extends ClassQuestionModel
{
    public function rules()
    {
        return [
            [['uid'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = ClassQuestionModel::find();

        $this->load($params);

        $query = $query->andFilterWhere
        (
            [
                'PubMan' => $this->uid,
            ]
        )->andFilterWhere(['like', 'Csame', $this->Csame]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'Csame' => SORT_ASC,
                ]
            ],
        ]);

        return $dataProvider;
    }
}
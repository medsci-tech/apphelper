<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/12
 * Time: 11:14
 */

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Guide as GuideModel;

class Guide extends GuideModel
{

    public function rules()
    {
        return [
            [['uid'], 'integer'],
        ];
    }

    public function search($params)
    {
        $query = GuideModel::find()->orderBy('id desc');

        $this->load($params);

        $query = $query->andFilterWhere
        (
            [
                'uid' => $this->uid,
            ]
        )->andFilterWhere(['like', 'title', $this->title]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'title' => SORT_ASC,
                ]
            ],
        ]);

        return $dataProvider;
    }
}
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
use common\models\Hospital as HospitalModel;

class Hospital extends HospitalModel
{

    public function search($params)
    {
        $query = HospitalModel::find()->orderBy('id desc');

        $this->load($params);

        $query = $query->andFilterWhere
        (
            [
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            ]
        )->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'name' => SORT_ASC,
                ]
            ],
        ]);

//            ->andFilterWhere(['like', 'category', $this->category])
//            ->andFilterWhere(['like', 'author', $this->author])
//            ->andFilterWhere(['like', 'cover', $this->cover]);


        return $dataProvider;
    }
}
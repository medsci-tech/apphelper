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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere([
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
//            ->andFilterWhere(['like', 'category', $this->category])
//            ->andFilterWhere(['like', 'author', $this->author])
//            ->andFilterWhere(['like', 'cover', $this->cover]);

        return $dataProvider;
    }
}
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
use common\models\Member as MemberModel;

class Member extends MemberModel
{
    public function rules()
    {
        return [
            [['username', 'province_id', 'city_id', 'area_id', 'hospital_id', 'rank_id'], 'integer'],
            [['username'], 'string', 'length' => 11],
            [['real_name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = MemberModel::find()->orderBy('id desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(
            [
                'username' => $this->username,
                'hospital_id' => $this->hospital_id,
            ]
        );
        $query->andFilterWhere(['like', 'real_name', $this->real_name]);
        return $dataProvider;
    }
}
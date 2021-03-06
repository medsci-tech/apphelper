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
            [['username', 'hospital_id'], 'integer'],
            [['real_name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = MemberModel::find();
        $this->load($params);
        $query->andFilterWhere(
            [
                'hospital_id' => $this->hospital_id,
            ]
        );
        $query->andFilterWhere(['like', 'real_name', $this->real_name]);
        $query->andFilterWhere(['like', 'username', $this->username]);
        return $query;
    }
}
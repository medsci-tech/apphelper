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

    public function search($params)
    {
        $query = MemberModel::find()->orderBy('id desc');
        $query = $query->andFilterWhere
        (
            [
            'username' => $this->username,
            'hospital_id' => $this->hospital_id,
            ]
        )->andFilterWhere(['like', 'real_name', $this->real_name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

//            ->andFilterWhere(['like', 'category', $this->category])
//            ->andFilterWhere(['like', 'author', $this->author])
//            ->andFilterWhere(['like', 'cover', $this->cover]);


        return $dataProvider;
    }
}
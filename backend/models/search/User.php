<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/28
 * Time: 14:06
 */

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User as UserModel;

class User extends UserModel {

    public function search($params)
    {
        $query = UserModel::find();
        $this->load($params);
        $where = [];
        if($this->uid){
            $where['uid'] = $this->uid;
        }
        $query = $query->andFilterWhere($where)
            ->andFilterWhere(['like', 'username', $this->username]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
        return $dataProvider;
    }

}
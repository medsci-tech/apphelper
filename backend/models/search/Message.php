<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 14:40
 */

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Message as MessageModel;

class Message extends MessageModel {

    public function search($params)
    {
        $query = MessageModel::find();
        $this->load($params);
        $where = [];
        if($this->uid){
            $where['uid'] = $this->uid;
        }
        $query = $query->andFilterWhere($where)
            ->andFilterWhere(['like', 'title', $this->title]);

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
<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\data\ActiveDataProvider;
use common\models\Video as VideoModel;

class Video extends VideoModel
{
    public function rules()
    {
        return [
            [['name', 'url'], 'string'],
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $query = VideoModel::find();
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'url', $this->url]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }
}
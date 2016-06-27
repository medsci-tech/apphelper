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

    public function rules()
    {
        return [
            [['province_id', 'city_id', 'area_id'], 'integer'],
            [['name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = HospitalModel::find();
        $this->load($params);
        $where = [];
        if($this->province_id){
            $where['province_id'] = $this->province_id;
            if($this->city_id){
                $where['city_id'] = $this->city_id;
                if($this->area_id){
                    $where['area_id'] = $this->area_id;
                }
            }
        }
        $query = $query->andFilterWhere($where)
            ->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                    'name' => SORT_ASC,
                ]
            ],
        ]);
        return $dataProvider;
    }
}
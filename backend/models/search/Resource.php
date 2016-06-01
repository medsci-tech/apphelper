<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\data\ActiveDataProvider;
use common\models\Resource as ResourceModel;
use common\models\ResourceClass;

class Resource extends ResourceModel
{
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['rid'], 'integer'],
        ];
    }

    public function search($params, $attr_type)
    {
        $this->load($params);
        $category = [];
        if($this->rid){
            $examClassFind = ResourceClass::find()->andFilterWhere(['like', 'path', ',' . $this->rid . ','])->asArray()->all();
        }else{
            $examClassFind = ResourceClass::find()->andFilterWhere(['attr_type' => $attr_type])->asArray()->all();
        }
//        $examClassFind = ResourceClass::find()->andFilterWhere(['like', 'path', ',' . $this->rid . ','])->asArray()->all();
        if(count($examClassFind) > 0){
            foreach ($examClassFind as $val){
                $category[] = $val['id'];
            }
        }
        $query = ResourceModel::find();
        $query->andFilterWhere(['rid'=> $category]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }
}
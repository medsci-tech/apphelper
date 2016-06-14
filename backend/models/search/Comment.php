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
use common\models\Resource;
use common\models\Exercise;
use common\models\Comment as CommentModel;
use common\models\ResourceClass;

class Comment extends CommentModel
{

    public function search($params)
    {

        if(isset($params['type'])){
            $title = $params['title'] ?? '';
            if($params['type'] == 'exercise'){
                //试题
                $query = Exercise::find();
                $query->andFilterWhere(['like', 'question', $title]);
            }else{
                //资源培训
                $examClassFind = ResourceClass::find()->andFilterWhere(['like', 'path', ',' . $params['type'] . ','])->asArray()->all();
                $category = [];
                if(count($examClassFind) > 0){
                    foreach ($examClassFind as $val){
                        $category[] = $val['id'];
                    }
                }
                $query = Resource::find();
                $query->andFilterWhere(['like', 'title', $title]);
                $query->andFilterWhere(['rid'=> $category]);
            }
        }else{
            //默认新手培训
            $examClassFind = ResourceClass::find()->andFilterWhere(['like', 'path', ',1,'])->asArray()->all();
            $category = [];
            if(count($examClassFind) > 0){
                foreach ($examClassFind as $val){
                    $category[] = $val['id'];
                }
            }
            $query = Resource::find();
            $query->andFilterWhere(['rid'=> $category]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

    /**
     * 评论详情搜索
     * @param $where
     * @return ActiveDataProvider
     */
    public function searchComment($where)
    {
        $model = $this::find();
        $model->andFilterWhere($where);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }
}
<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Class Exercise
 * @package common\models
 */
class Exercise extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%exercise}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type', 'question', 'category', 'option', 'answer'], 'required'],
            [['question'], 'string', 'max' => 20],
            [['answer'], 'string', 'max' => 10],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => '类型',
            'category' => '类别',
            'question' => '题目',
            'option' => '选项',
            'answer' => '答案',
            'keyword' => '关键词',
            'resolve' => '解析备注',
            'status' => '状态',
            'created_at' => '创建时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
//        $scenarios['update'] = ['username', 'email'];
        return $scenarios;
    }

    public function search($params)
    {
        $this->load($params);
        $query = $this::find()->orderBy('id desc');
        $query->andFilterWhere(['like', 'question', $this->question]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

  


}

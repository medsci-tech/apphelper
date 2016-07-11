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
            [['type', 'question', 'category', 'option', 'answer', 'keyword', 'resolve'], 'required','message'=>'不能为空'],
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

    public function getDataForWhere($where = []){
        $where['status'] = 1;
        $examClass = $this::find()->where($where)->asArray()->all();
        return $examClass;
    }
  


}

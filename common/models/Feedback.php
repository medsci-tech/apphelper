<?php

namespace common\models;


use Yii;

/**
 * Class Feedback
 * @package common\models
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%feedback}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['imgurl', 'content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => '创建时间',
            'content' => '内容',
            'imgurl' => '图片',
            'real_name' => '姓名',
            'nickname' => '昵称',
            'username' => '手机号',
        ];
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/6/6
 * Time: 9:53
 */

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

class Message extends \yii\db\ActiveRecord {

    public static function tableName()
    {
        return '{{%message}}';
    }

    public function rules()
    {
        return [
            [['uid', 'isread', 'push_type','created_at','status','send_at','touid' ], 'integer'],
            [['title'], 'string', 'max' => 150],
            [['link_url'], 'string', 'max' => 150],
            [['content'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => '标题',
            'link_url' => '跳转链接',
            'content' => '内容',
            'push_type' => '推送范围',
            'created_at' => '创建时间',
            'status' => '发送状态',
            'send_at' => '发送时间',
            'uid' => '使用人',
        ];
    }
}
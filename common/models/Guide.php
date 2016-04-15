<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/12
 * Time: 10:22
 */

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;


class Guide extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%guide}}';
    }

    public function rules()
    {
        return [
//            [['name', 'address'], 'required'],
            [['id', 'uid', 'created_at'], 'integer'],
            [['title'], 'string', 'max' => 20],
            [['url'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => '标题',
            'url' => '链接地址',
            'uid' => 'uid',
            'created_at' => '创建时间',
        ];
    }



}
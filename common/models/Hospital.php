<?php

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%hospital}}".
 *
 * @property int $id
 * @property string $parent
 * @property string $name
 * @property string $author

 */
class Hospital extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hospital}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['province_id', 'city_id', 'area_id'], 'integer'],
//            ['status', 'default', 'value' => self::STATUS_ACTIVE],
//            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INIT]],
            [['province_id'], 'setProvince'],
            [['city_id'], 'setCity'],
            [['area_id'], 'setArea'],
            [['name', 'address'], 'string', 'max' => 30],
            [['province', 'city', 'area'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => '名称',
            'province_id' => '省份',
            'province' => '省份',
            'city_id' => '城市',
            'city' => '城市',
            'area_id' => '县区',
            'area' => '县区',
            'address' => '地址',
        ];
    }

    public function setProvince($attribute, $params)
    {
        $this->province = Region::find()->where(['id' => $this->province_id])->select('name')->scalar();
    }

    public function setCity($attribute, $params)
    {
        $this->city = Region::find()->where(['id' => $this->city_id])->select('name')->scalar();
    }

    public function setArea($attribute, $params)
    {
        $this->area = Region::find()->where(['id' => $this->area_id])->select('name')->scalar();
    }

    public function getProvince()
    {
        return $this->hasOne(Region::className(), ['id' => 'province_id']);
    }

    public function getCity()
    {
        return $this->hasOne(Region::className(), ['id' => 'city_id']);
    }

    public function getArea()
    {
        return $this->hasOne(Region::className(), ['id' => 'area_id']);
    }
}

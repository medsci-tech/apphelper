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
//            [['category_id'], 'setCategory'],
            [['name', 'address'], 'string', 'max' => 30],
//            [['author', 'cover'], 'string', 'max' => 255],
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
            'city_id' => '城市',
            'area_id' => '县区',
            'address' => '地址',
        ];
    }

//    public function getData($province, $city, $area)
//    {
//        return Hospital::find()->andFilterWhere(['province_id' => $province, 'city_id' => $city, 'area_id' => $area])->all();
//    }
}

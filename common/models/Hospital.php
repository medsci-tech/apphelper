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
            [['name', 'address'], 'string', 'max' => 40],
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
            'status' => '状态',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 根据条件查询
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDataForWhere($where = []){
        $where['status'] = 1;
        $examClass = $this::find()->where($where)->asArray()->all();
        return $examClass;
    }
}

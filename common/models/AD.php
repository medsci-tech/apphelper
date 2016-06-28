<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/4
 * Time: 15:48
 */

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;


class AD extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%ad}}';
    }

    public function rules()
    {
        return [
            [['created_at', 'attr_id', 'attr_from', 'status'], 'integer'],
            [['title'], 'string', 'max' => 20],
            [['imgurl'], 'string', 'max' => 150],
            // 若 "linkurl" 为空，则设其为 null
            ['linkurl', 'default', 'value' => null]
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
            'imgurl' => '图片地址',
            'sort' => '显示顺序',
            'attr_id' => '资源地址',
            'attr_from' => '资源来源',
            'status' => '是否启用',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(Yii::$app->cache->exists(Yii::$app->params['redisKey'][1])) {
            Yii::$app->cache->delete(Yii::$app->params['redisKey'][1]);
        }
//        if($insert) {
//            //这里是新增数据
//        } else {
//            //这里是更新数据
//        }
    }

}
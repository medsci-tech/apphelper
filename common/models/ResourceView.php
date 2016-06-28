<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/16
 * Time: 16:00
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class ResourceView extends ActiveRecord {

    public static function tableName()
    {
        return '{{%resource_view_log}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => '资源名',
            'attr_type' => '类别',
            'real_name' => '姓名',
            'nickname' => '昵称',
            'username' => '手机号',
            'view' => '浏览数',
            'times' => '时长',
            'created_at' => '浏览时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

}
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


class Video extends ActiveRecord {

    public static function tableName()
    {
        return '{{%video}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'name',
                'url',
            ], 'required','message'=>'不能为空'],
            [[
                'name',
                'url',
                'type',
            ], 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => '文件名',
            'url' => '链接地址',
            'type' => '类型',
            'created_at' => '创建时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }
    

}
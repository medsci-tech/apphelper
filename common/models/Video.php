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
                'suffix',
            ], 'required'],
            [[
                'name',
                'url',
                'suffix',
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
            'suffix' => '后缀',
            'created_at' => '创建时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * 批量修改
     * author zhaiyu
     * startDate 20160511
     * updateDate 20160511
     * @param array $where
     * @param array $data
     */
    public function saveData($where = [], $data = []){
        $exam = $this::find()->where($where)->all();
        foreach ($exam as $val){
            foreach ($data as $k => $v){
                $val->$k = $v;
            }
            $val->save(false);
        }
    }

}
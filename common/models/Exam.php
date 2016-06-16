<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Class Exercise
 * @package common\models
 */
class Exam extends ActiveRecord
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type', 'name', 'minutes', 'uid', 'about', 'count', 'status', 'publish_status', 'recommend_status'], 'required'],
            [[ 'class_id', 'total'], 'integer'],
            [[ 'imgurl'], 'string'],
            // 若 "imgurl" 为空，则设其为 null
            ['imgurl', 'default', 'value' => null],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => '类型',
            'name' => '名称',
            'minutes' => '时长',
            'exe_ids' => '题库',
            'uid' => '用户ID',
            'about' => '简介',
            'imgUrl' => '封面图',
            'count' => '参与人数',
            'status' => '状态',
            'publish_status' => '发布状态',
            'recommend_status' => '推荐状态',
            'created_at' => '创建时间',
            'publish_time' => '发布时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    public function saveData($where = [], $data = []){
        $exam = Exam::find()->where($where)->all();
        foreach ($exam as $val){
            foreach ($data as $k => $v){
                $val->$k = $v;
            }
            $val->save(false);
        }
    }



}

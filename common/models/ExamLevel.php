<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Exercise
 * @package common\models
 */
class ExamLevel extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%exam_level}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['level', 'condition', 'rate', 'remark', 'exam_id'], 'required','message'=>'不能为空'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level' => '级别',
            'condition' => '条件',
            'rate' => '正确率',
            'remark' => '评分',
        ];
    }

    public function getDataForWhere($where = []){
        $examClass = ExamClass::find()->where($where)->orderBy(['rate' => SORT_DESC])->asArray()->all();
        return $examClass;
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

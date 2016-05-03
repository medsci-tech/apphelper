<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * Class Exercise
 * @package common\models
 */
class ExamClass extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%exam_class}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['parent', 'path', 'grade', 'name', 'uid', 'sort', 'status'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent' => '父级',
            'path' => '节点',
            'grade' => '层级',
            'name' => '名称',
            'uid' => '用户ID',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '创建时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    public function getDataForWhere($where = []){
        $examClass = ExamClass::find()->where(array_merge(['status' => 1], $where))->orderBy(['sort' => SORT_DESC])->asArray()->all();
        return $examClass;
    }

    /*树形结构*/
    public function recursionTree($parent = 0){
        $column = [];
        $model = $this->getDataForWhere(['parent' => $parent]);
        if(is_array($model)){
            foreach ($model as $key => $val){
                $column[$key]['id'] = $val['id'];
                $column[$key]['text'] = $val['name'];
                $column[$key]['nodes'] = $this->recursionTree($val['id']);
                if(empty($column[$key]['nodes'])){
                    unset($column[$key]['nodes']);
                }
            }
        }
        return $column;
    }


}

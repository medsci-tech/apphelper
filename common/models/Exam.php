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
     * @return string
     */
    public static function tableName()
    {
        return '{{%exam}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type', 'name', 'minutes', 'exe_ids', 'uid', 'about', 'imgUrl', 'count', 'status', 'publish_status', 'recommend_status'], 'required'],
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
            'minutes' => '时间',
            'exe_ids' => '题库',
            'uid' => '用户ID',
            'about' => '简介',
            'imgUrl' => '封面图',
            'count' => '参与人数',
            'status' => '状态',
            'publish_status' => '发布状态',
            'recommend_status' => '推荐状态',
            'created_at' => '创建时间',
            'publish_at' => '发布时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    public function getDataForWhere($where = []){
        $where['status'] = 1;
        $examClass = ExamClass::find()->where($where)->orderBy(['sort' => SORT_DESC])->asArray()->all();
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

<?php

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%ResourceClass.}}".
 *
 */
class ResourceClass extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INIT = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%resource_class}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'parent', 'grade', 'uid', 'attr_type', 'sort'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INIT]],
            [['name'], 'string', 'max' => 50],
            [['path'], 'string', 'max' => 20],
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
            'author' => '作者',
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'category_id' => '分类',
            'category' => '分类',
        ];
    }


    public function getDataForWhere($where = []){
        $where['status'] = 1;
        $examClass = $this::find()->where($where)->orderBy(['sort' => SORT_DESC])->asArray()->all();
        return $examClass;
    }

    /*树形结构*/
    public function recursionTree($parent = 0, $where = []){
        $column = [];
        $where['parent'] = $parent;
        $model = $this->getDataForWhere($where);
        if(is_array($model)){
            foreach ($model as $key => $val){
                $column[$key]['id'] = $val['id'];
                $column[$key]['text'] = $val['name'];
                $column[$key]['nodes'] = $this->recursionTree($val['id'], $where = []);
                if(empty($column[$key]['nodes'])){
                    unset($column[$key]['nodes']);
                }
            }
        }
        return $column;
    }
}

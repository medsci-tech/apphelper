<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/20
 * Time: 10:28
 */

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

class ClassQuestion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%classquestion}}';
    }

    public function rules()
    {
        return [
            [['id', 'Sort', 'Pubdate','PubMan','Pubflag','Ctype'], 'integer'],
            [['SourceID','AppID'], 'string', 'max' => 30],
            [['Csame', 'Csdesc'], 'string', 'max' => 400],
            [['Remark'], 'string', 'max' => 200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'Ctype' => '题型',
            'Csame' => '考题',
            'Enable' => '是否启用',
            'PubMan' => 'uid',
            'Pubdate' => '创建时间',
        ];
    }
}
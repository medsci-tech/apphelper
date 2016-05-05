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
}
<?php

namespace common\models;

use common\behaviors\AfterFindArticleBehavior;
use common\behaviors\PushBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%hospital}}".
 *
 * @property int $id
 * @property string $parent
 * @property string $name
 * @property string $author

 */
class Region extends \yii\db\ActiveRecord
{
    public $province_id;
    public $city_id;
    public $area_id;

    /**
     * @param $pid
     * @param $grade
     * @return array
     */
    public function getRegionList($pid, $grade = 1)
    {
        $model = Region::findAll(['parent'=>$pid,'grade' => $grade]);
        return ArrayHelper::map($model, 'id', 'name');
    }

    //

    /**
     * @abstract 返回多个区域
     * @arr 区域id数组集合
     * @return void or String
     * @author by lxhui
     * @version [2016-03-05]
     */
    public function getRegions($arr)
    {
        $model = Region::findAll(['in', 'id', $arr]);
        return ArrayHelper::map($model, 'id', 'name');
    }
}

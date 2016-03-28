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
     * @return array
     */
    public function getRegionList($pid)
    {
        $model = Region::findAll(array('parent'=>$pid));
        return ArrayHelper::map($model, 'id', 'name');
    }

}

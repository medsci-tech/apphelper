<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/3/23
 * Time: 14:03
 */

namespace backend\models\search;

use yii\data\ActiveDataProvider;
use common\models\Resource as ResourceModel;
use common\models\ResourceClass;

class Resource extends ResourceModel
{
    public function rules()
    {
        return [
            [['title'], 'string'],
        ];
    }

    public function search($params)
    {

    }
}
<?php

namespace backend\models;
use Yii;

/**
 * This is the model class for table "app".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $content
 * @property integer $created
 */
class App extends \yii\db\ActiveRecord
{

}
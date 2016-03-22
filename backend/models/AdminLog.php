<?php

namespace backend\models;
use Yii;

/**
 * This is the model class for table "reply".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $topic_id
 * @property string $content
 * @property integer $created
 */
class AdminLog extends \yii\db\ActiveRecord
{
    public function saves()
    {
        $this->ip = Yii::$app->request->getUserIP();
        $this->logintime = time();
        $this->uid = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id;
        return $this->save();
    }
}
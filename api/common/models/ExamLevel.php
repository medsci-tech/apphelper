<?php
namespace api\common\models;
use Yii;
use yii\db\Query;
use yii\helpers\Html;
class ExamLevel extends \yii\db\ActiveRecord
{
    /**
     * 返回关联试卷名
     * @author by lxhui
     * @version [2010-05-11]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function getExam()
    {
        return $this->hasOne(EXAM::className(), ['id' => 'exam_id']);
    }

}
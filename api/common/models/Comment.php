<?php
namespace api\common\models;

use Yii;
use yii\db\Query;
use yii\helpers\Html;
class Comment extends \yii\db\ActiveRecord
{
    public function rules()
    {
        return [
            ['rid', 'required','message' => '评论资源rid不能为空!'],
            ['content', 'required','message' => '说点什么吧!'],
                ['type', 'required'],
            [['rid', 'reply_to_uid','uid'], 'integer'],
            ['reply_to_uid', 'default', 'value' => 0],
            ['created_at', 'default', 'value' => time()],
            ['type', 'in', 'range' => ['exam','resource']],
            [['content'], 'string', 'max' => 500]
        ];
    }
    /**
     * 保存评论
     * @author by lxhui
     * @version [2010-05-05]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function saves()
    {
        if ($this->validate()) {
            if ($this->save()) {
                return $this;
            }
        }
        return false;
    }
}
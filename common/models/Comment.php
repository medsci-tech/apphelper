<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property int $id
 * @property int $article_id
 * @property int $user_id
 * @property string $content
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'user_id', 'content'], 'required'],
            [['article_id', 'user_id', 'parent_id', 'up', 'down'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'user_id' => 'User ID',
            'content' => '内容',
            'up' => '顶',
            'down' => '踩',
            'real_name' => '姓名',
            'nickname' => '昵称',
            'username' => '手机号',
            'to-real_name' => '被回复者-姓名',
            'to-nickname' => '被回复者-昵称',
            'to-username' => '被回复者-手机号',
            'comments' => '评论数',
            'created_at' => '创建时间',
            'status' => '状态',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * 获取发表评论的用户信息.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 获取所有子评论.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSons()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    /**
     * 绑定写入后的事件.
     */
    public function init()
    {
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'addComment']);
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT,
        ];
    }

    /**
     * 更新文章评论计数器.
     */
    public function addComment()
    {
        $article = Article::find()->where(['id' => $this->article_id])->one();
        $article->updateCounters(['comment' => 1]);
    }

    /**
     * 批量修改
     * author zhaiyu
     * startDate 20160511
     * updateDate 20160511
     * @param array $where
     * @param array $data
     */
    public function saveData($where = [], $data = []){
        $result = $this::find()->where($where)->all();
        foreach ($result as $val){
            foreach ($data as $k => $v){
                $val->$k = $v;
            }
            $val->save(false);
        }
    }
}

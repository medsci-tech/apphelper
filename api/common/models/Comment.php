<?php
namespace api\common\models;
use yii\web\IdentityInterface;
use yii\base\Model;
class Comment extends \yii\db\ActiveRecord
{
    public function rules()
    {
        return [
            ['rid', 'required','message' => '评论对象rid不能为空!'],
            ['content', 'required','message' => '说点什么吧!'],
            ['type', 'required','message' => '评论类型type错误!'], 
            [['rid', 'reply_to_uid','uid','parent_uid'], 'integer'],
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

    /**
     * 根据条件获取数据列表
     * @param array $where
     * @param array $limit
     * @param array $offset
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDataForWhere($where = [], $offset = 0, $limit = 10){
        $where['status'] = 1;
        $dataList = $this::find()->where($where)->offset($offset)->limit($limit)->all();
        return $dataList;
    }

    /**
     * 根据条件获取数据条数
     * @param array $where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDataCountForWhere($where = []){
        $where['status'] = 1;
        $dataList = $this::find()->where($where)->count();
        return $dataList;
    }
}
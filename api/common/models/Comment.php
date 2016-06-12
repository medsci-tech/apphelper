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
            [['rid', 'cid','uid','exa_id'], 'integer'],
            ['reply_to_uid', 'default', 'value' => 0],
            ['created_at', 'default', 'value' => time()],
            ['type', 'in', 'range' => ['exam','resource']],
            ['exa_id', 'required', 'when' => function($model) {
            return $model->type == 'exam';
            },'message' => '试卷id不能为空!'],
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
     * @param int $offset
     * @param int $limit
     * @param array $orderBy
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getDataForWhere($where = [], $offset = 0, $limit = 10, $orderBy = []){
        $where['status'] = 1;
        $orderBy['id'] = SORT_DESC;
        if($limit){
            $dataList = $this::find()->where($where)->offset($offset)->limit($limit)->orderBy($orderBy)->all();
        }else{
            $dataList = $this::find()->where($where)->orderBy($orderBy)->all();
        }
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
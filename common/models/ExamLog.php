<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/5/16
 * Time: 16:00
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class ExamLog extends ActiveRecord {

    public static function tableName()
    {
        return '{{%exam_log}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => '试卷名',
            'real_name' => '姓名',
            'nickname' => '昵称',
            'username' => '手机号',
            'level' => '答题成绩',
            'rate' => '正确率',
            'times' => '答题时间',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    /**
     * 资源列表搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchExam($params)
    {
        $query = $this::find();
        $startTime = $params['startTime'] ?? '';
        $endTime = $params['endTime'] ?? '';
        $name = $params['name'] ?? '';
        if($startTime){
            $query->andFilterWhere(['>=', 'end_time', strtotime($startTime)]);
        }
        if($endTime){
            $query->andFilterWhere(['<=', 'start_time', strtotime($endTime)]);
        }
        if($name){
            $examModel = Exam::find()->where(['like', 'name' ,$name])->all();
            $exa_id = [];
            if($examModel){
                foreach ($examModel as $key => $val){
                    $exa_id[] = $val->id;
                }
            }
            if($exa_id){
                $query->andFilterWhere(['exa_id' => $exa_id]);
            }else{
                //搜索为空
                $query->andFilterWhere(['id' => '']);
            }
        }
        $query->andFilterWhere(['status' => 1]);//查询提交的试卷
        return $query;
    }

    public function searchExamInfo($params)
    {
        $query = $this::find();
        $username = $params['username'] ?? '';
        $exa_id = $params['exa_id'];
        $where = [];
        if($username){
            $memberModel = Member::find()->where(['like', 'username', $username])->all();
            if($memberModel){
                foreach ($memberModel as $key => $val){
                    $where['uid'][] = $val->id;
                }
            }else{
                $where['id'][] = '';
            }
        }
        $where['exa_id'] = $exa_id;
        $where['status'] = 1;
        $query->andFilterWhere($where);
        return $query;
    }

    /**
     * 资源列表搜索
     * @param $params
     * @return ActiveDataProvider
     */
    public function searchExuser($params)
    {
        $query = $this::find();
        $startTime = $params['startTime'] ?? '';
        $endTime = $params['endTime'] ?? '';
        $username = $params['username'] ?? '';
        if($startTime){
            $query->andFilterWhere(['>=', 'end_time', strtotime($startTime)]);
        }
        if($endTime){
            $query->andFilterWhere(['<=', 'start_time', strtotime($endTime)]);
        }
        if($username){
            $examModel = Member::find()->where(['like', 'username' ,$username])->all();
            $idArray = [];
            if($examModel){
                foreach ($examModel as $key => $val){
                    $idArray[] = $val->id;
                }
            }
            if($idArray){
                $query->andFilterWhere(['uid' => $idArray]);
            }else{
                //搜索为空
                $query->andFilterWhere(['id' => '']);
            }
        }
        $query->andFilterWhere(['status' => 1]);//查询提交的试卷
        return $query;
    }

    public function searchExuserInfo($params)
    {
        $query = $this::find();
        $name = $params['name'] ?? '';
        $uid = $params['uid'];
        $where = [];
        if($name){
            $memberModel = Exam::find()->where(['like', 'name', $name])->all();
            if($memberModel){
                foreach ($memberModel as $key => $val){
                    $where['exa_id'][] = $val->id;
                }
            }else{
                $where['id'][] = '';
            }
        }
        $where['uid'] = $uid;
        $where['status'] = 1;
        $query->andFilterWhere($where);
        return $query;
    }

}
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

class ResourceStudy extends ActiveRecord {

    public static function tableName()
    {
        return '{{%resource_study_log}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [[
                'created_at',
                'rid',
                'times',
            ], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => '资源名',
            'attr_type' => '类别',
            'real_name' => '姓名',
            'nickname' => '昵称',
            'username' => '手机号',
            'view' => '浏览数',
            'times' => '时长',
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
    public function search($params)
    {
        $query = $this::find();
        $startTime = $params['startTime'] ?? '';
        $endTime = $params['endTime'] ?? '';
        $title = $params['title'] ?? '';
        $attr_type = $params['attr_type'] ?? 0;
        if($startTime){
            $query->andFilterWhere(['>=', 'created_at', strtotime($startTime)]);
        }
        if($endTime){
            $query->andFilterWhere(['<=', 'created_at', strtotime($endTime)]);
        }
        $classResourceModel = (new ResourceClass())->getDataForWhere(['attr_type' => $attr_type]);
        $typeList = [];
        foreach ($classResourceModel as $key => $val){
            $typeList[] = $val['id'];
        }
        $resourceModel = Resource::find()->where(['rid' => $typeList])->andWhere(['like', 'title' ,$title])->all();
        $resourceStudyWhere = [];
        foreach ($resourceModel as $key => $val){
            $resourceStudyWhere[] = $val->id;
        }
        if($resourceStudyWhere){
            $query->andFilterWhere(['rid' => $resourceStudyWhere]);
        }else{
            //搜索为空
            $query->andFilterWhere(['id' => '']);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

    public function searchResourceForResource($params)
    {
        $query = $this::find();
        $username = $params['username'] ?? '';
        $rid = $params['rid'] ?? '';
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
        if($rid){
            $where['rid'] = $rid;
        }
        $query->andFilterWhere($where);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => \Yii::$app->params['pageSize'],
            ],
        ]);
        return $dataProvider;
    }

}
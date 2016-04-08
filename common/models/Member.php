<?php

namespace common\models;

use Yii;

/**
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $real_name
 * @property string $province_id
 * @property string $city_id
 * @property string $area_id
 * @property string $hospital_id
 * @property string $rank_id
 * @property string $create_at

 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'real_name', 'province_id', 'city_id', 'area_id', 'hospital_id', 'rank_id'], 'required'],
            [['username', 'province_id', 'city_id', 'area_id', 'hospital_id', 'rank_id'], 'integer'],
            [['username'], 'string', 'length' => 11],
            [['real_name'], 'string', 'max' => 30],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => '手机号',
            'province_id' => '省份',
            'province' => '省份',
            'city_id' => '城市',
            'city' => '城市',
            'area_id' => '县区',
            'area' => '县区',
            'address' => '地址',
            'hospital_id' => '医院',
            'rank_id' => '职称',
            'email' => '邮箱',
            'created_at' => '注册时间',
            'updated_at' => '编辑时间',
            'real_name' => '姓名',
            'status' => '状态',
        ];
    }
    /**
     * 添加用户
     * @param $params
     * @return array|bool
     * @throws \Exception
     * @author zhaiyu
     * @startDate 20160406
     * @upDate 20160406
     */
    public function add($params)
    {
        if ($this->validate()) {
            // 验证成功！
            $this->username = $params['username'];
            $this->email = $params['email'];
            $this->real_name = $params['real_name'];
            $this->province_id = $params['province_id'];
            $this->city_id = $params['city_id'];
            $this->area_id = $params['area_id'];
            $this->hospital_id = $params['hospital_id'];
            $this->rank_id = $params['rank_id'];
            $this->create_at = time();
            $res = $this->insert();
            if($res){
                $return = [
                    'id' => $this->id,
                ];
            }else{
                $return = false;
            }
        } else {
            $return = $this->getErrors();
            // 失败！
            // 使用 $model->getErrors() 获取错误详情
        }

        return $return;
    }

    /**
     * 编辑用户信息
     * @param $attribute
     * @param $params
     * @return bool|int
     * @throws \Exception
     * @author zhaiyu
     * @startDate 20160406
     * @upDate 20160406
     */
    public function edit($attribute, $params)
    {
        $class = $this::find()->where($attribute)->one();
        $this->username = $params['username'];
        $this->email = $params['email'];
        $this->real_name = $params['real_name'];
        $this->province_id = $params['province_id'];
        $this->city_id = $params['city_id'];
        $this->area_id = $params['area_id'];
        $this->hospital_id = $params['hospital_id'];
        $this->rank_id = $params['rank_id'];
        $this->create_at = time();
        return $class->update();

    }
}

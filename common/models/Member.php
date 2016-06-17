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
class Member extends User
{
    public $devicetoken;
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
            [['username', 'email', 'real_name', 'province_id', 'city_id', 'hospital_id', 'rank_id'], 'required'],
            [['province_id', 'city_id', 'area_id', 'hospital_id', 'rank_id'], 'integer'],
            [['area', 'city', 'province'], 'string'],
            [['username'], 'string', 'length' => 11],
            [['real_name'], 'string', 'max' => 30],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => '手机号',
            'nickname' => '昵称',
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

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['username', 'email'];
        $scenarios['resetPassword'] = ['password'];
        return $scenarios;
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    /**
     * 添加用户
     * @param $params
     * @return array|bool
     * @throws \Exception
     * @author zhaiyu
     * @startDate 20160406
     * @upDate 20160406
     */
    public function signup()
    {
        if ($this->validate()) {
            $this->username;
            $this->email;
            if(isset($this->password)){
                $password = $this->password;
            }else{
                $password = Yii::$app->params['member']['defaultPwd'];
            }
            $this->setPassword($password);
            $this->generateAuthKey();
            if ($this->save()) {
                return $this;
            }
        }
        return false;
    }

    public function resetPassword()
    {
        $this->setPassword($this->password);
        unset($this->password);
        return $this->save(false);
    }

    public function checkUsernameExist($username, $id = 0){
        $where = [
            'username' => $username
        ];
        if($id > 0){
            $andWhere = 'id != '.$id;
        }else{
            $andWhere = '';
        }
        $result = $this::find()->where($where)->andWhere($andWhere)->one();
        if($result){
            return true;
        }else{
            return false;
        }
    }

}

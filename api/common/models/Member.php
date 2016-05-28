<?php

namespace api\common\models;

use backend\models\User;
use common\models\Region;
use Yii;
use yii\web\IdentityInterface;
use yii\base\Model;
/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $avatar
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $verycode;
    public $password;
    public $passwordRepeat;
    public $uid;
    public $nickname;
    public $real_name;
    private $_user = false;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;

    /**
     * @abstract 用户注册登录场景
     * @return void or String
     * @author by lxhui
     * @version [2016-03-24]
     */
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['username', 'password', 'verycode']; // 注册
        $scenarios['next'] = ['uid', 'nickname', 'sex', 'province', 'city', 'area','hospital_id','rank_id']; // 注册下一步
        $scenarios['login'] = ['username', 'password']; // 登录
        $scenarios['setPassword'] = ['username','verycode','password', 'passwordRepeat']; // 设置密码
        $scenarios['setNickname'] = ['uid', 'nickname']; // 修改昵称
        $scenarios['setUsername'] = ['uid', 'username','verycode']; // 修改用户手机号
        $scenarios['setRealname'] = ['uid', 'real_name']; // 修改真实姓名
        $scenarios['setSex'] = ['uid', 'sex']; // 修改性别
        $scenarios['setHospital'] = ['uid', 'hospital_id']; // 修改单位
        $scenarios['setRank'] = ['uid', 'rank_id']; // 修改职称
        $scenarios['setRegion'] = ['uid','province', 'city','area']; // 修改区域
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required','message' => '请输入手机号!', 'on' => ['register','login','setPassword','setUsername']],
            [['verycode'], 'required','message' => '请输入验证码!'],
            [['username'], 'match', 'pattern' => '/^1[3|4|5|7|8][0-9]{9}$/','message' => '请输入有效的手机号!'],
            ['username', 'unique', 'targetClass' => '\api\common\models\Member', 'message' => '该手机号已经存在!', 'on' => ['register']],
            [['verycode'], function ($attribute, $params) {
                $verycode = Yii::$app->cache->get($this->username);
                if ($verycode !== $this->verycode) {
                    $this->addError($attribute, '手机验证码不匹配或者已过期！');
                }
            }],
            ['password', 'string', 'min' => 6, 'max' => 24,'message' => '密码长度在6-12之间!'],
            ['password', 'validatePassword', 'on' => 'login'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'avatar', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            //注册资料场景
            [['username', 'password', 'verycode'], 'required', 'on' => ['register']], //必填

            /* 设置密码相关 */
            [['username', 'password'], 'required', 'message' => '用户或密码不能为空!', 'on' => 'login'],
            [['password', 'passwordRepeat'], 'string', 'min' => 6, 'max' => 12, 'message' => '{attribute}是6-12位数字或字母'],
            //[['password', 'passwordRepeat'], 'string', 'min' => 6, 'max' => 24],
            [['password', 'passwordRepeat'], 'required', 'message' => '密码和确认密码不能为空!', 'on' => 'setPassword'],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致!', 'on' => 'setPassword'],

            /* 个人资料相关 */
            [['uid'], 'required', 'message' => 'uid不能为空!', 'on' => ['setNickname','setRealname','next','sexSex']],
            [[ 'nickname'], 'required', 'message' => '昵称不能为空!', 'on' => ['setNickname','next']],
            [['nickname'], 'string', 'max' => 20,'message' => '昵称不能超过20个字符!'],
            [['real_name'], 'required', 'message' => '真实姓名不能为空!', 'on' => 'setRealname'],
            [['sex'], 'required', 'message' => '性别不能为空!', 'on' => 'setSex'],
            [['hospital_id'], 'required','message' => '药店不能为空!', 'on' => ['setHospital','next']],
            [['rank_id'], 'required','message' => '职称不能为空!', 'on' => ['setRank','next']],
            [['province'], 'required','message' => '省份不能为空!', 'on' => ['setRegion','next']],
            [['province', 'city', 'area'], 'string'],

            /* 注册下一步 */
            [['sex','province','hospital_id','rank_id'], 'required', 'on' => 'next'],
            ['sex', 'in', 'range' => ['男','女'], 'on' => ['next','setSex']],
            [['city', 'area'], 'default', 'on' => ['next','setRegion']],// 若 "city" 和 "area" 为空，则设为 null

        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }


    // 返回的数据格式化
    public function fields()
    {
        $fields = parent::fields();

        // remove fields that contain sensitive information
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);

        return $fields;
    }
    /* 登录相关 */
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePass($this->password)) {
                $this->addError($attribute, '用户名或密码错误!');
            }
        }
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePass($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Member::findByUsername($this->username);
        }

        return $this->_user;
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * Finds user by uid
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUid($uid)
    {
        return static::findOne(['id' => $uid, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * 修改资料
     * @author by lxhui
     *@param username 用户名
     * @param verycode 验证码
     * @version [2016-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function editProfile() {
        if ($this->validate()) {
            $regionModel = new Region();
            $data = [
                'nickname'=>$this->nickname,
                'sex'=>$this->sex,
                'province'=>$this->province,
                'city'=>$this->city,
                'area'=>$this->area,
                'hospital_id'=>$this->hospital_id,
                'rank_id'=>$this->rank_id,
            ];
            $region = $regionModel->getByName($this->province,$this->city,$this->area);
            $data['province_id'] = $region[0]['code'];
            $data['city_id'] = $region[1]['code'] ?? '';
            $data['area_id'] = $region[2]['code'] ?? '';
            $user = $this->updateAll($data,'id=:id',array(':id'=>$this->uid));
            return true;
        }
        return false;

    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            /* 更新tocken */
            $this->_user->access_token = Yii::$app->security->generateRandomString();
            $this->updateAll(['access_token'=>$this->_user->access_token],'id=:id',array(':id'=>$this->_user->id));
            return $this->_user;
        } else {
            return false;
        }
    }
    public function signup()
    {
        if ($this->validate()) {
            $this->username = $this->username;
            $this->access_token = Yii::$app->security->generateRandomString();
            $this->setPassword($this->password);
            $this->created_at = time();
            $this->generateAuthKey();
            if ($this->save()) {
                return $this;
            }
        }
        return false;
    }

    /**
     * Resets password.
     * $step 操作步骤
     * @return boolean if password was reset.
     */
    public function changePassword()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = Member::find()->where(['username'=>$this->username])->one();
        $user->setPassword($this->password);
        if ($user->save(false)) {
            return $user;
        }
    }
    /**
     * Resets nickname
     * @return boolean if password was reset.
     */
    public function changeNickname()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = $this->updateAll(['nickname'=>$this->nickname],'id=:id',array(':id'=>$this->uid));
        return true;
    }

    /**
     * Resets real_name
     * @return boolean if password was reset.
     */
    public function changeRealname()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = $this->updateAll(['real_name'=>$this->real_name],'id=:id',array(':id'=>$this->uid));
        return true;
    }

    /**
     * Resets username
     * @return boolean if password was reset.
     */
    public function changeUsername()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = $this->updateAll(['username'=>$this->username],'id=:id',array(':id'=>$this->uid));
        return true;
    }
    /**
     * Resets sex
     * @return boolean if password was reset.
     */
    public function changeSex()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = $this->updateAll(['sex'=>$this->sex],'id=:id',array(':id'=>$this->uid));
        return true;
    }

    /**
     * Resets province city area
     * @return boolean if password was reset.
     */
    public function changeRegion()
    {
        if ( !$this->validate()) {
            return false;
        }
        $regionModel = new Region();
        $data = [
            'province'=>$this->province,
            'city'=>$this->city,
            'area'=>$this->area
        ];
        $region = $regionModel->getByName($this->province,$this->city,$this->area);
        $data['province_id'] = $region[0]['code'];
        $data['city_id'] = $region[1]['code'] ?? '';
        $data['area_id'] = $region[2]['code'] ?? '';
        $user = $this->updateAll($data,'id=:id',array(':id'=>$this->uid));
        return true;
    }
    /**
     * Resets hospital
     * @return boolean if password was reset.
     */
    public function changeHospital()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = $this->updateAll(['hospital_id'=>$this->hospital_id],'id=:id',array(':id'=>$this->uid));
        return true;
    }
    /**
     * Resets hospital
     * @return boolean if password was reset.
     */
    public function changeRank()
    {
        if ( !$this->validate()) {
            return false;
        }
        $user = $this->updateAll(['rank_id'=>$this->rank_id],'id=:id',array(':id'=>$this->uid));
        return true;
    }


    public function afterSave1($insert)
    {
        parent::afterSave($insert);
        if ($this->getScenario() === 'userCreates') {
            // FIXME: TODO: WIP, TBD
        }
    }

}

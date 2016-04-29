<?php

namespace api\common\models;

use Yii;
use yii\web\IdentityInterface;

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
    /**
     * @abstract 用户注册登录场景
     * @return void or String
     * @author by lxhui
     * @version [2016-03-24]
     */
    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['register'] = ['username', 'password', 'verycode']; // 注册
        $scenarios['login'] = ['username', 'password']; // 登录
        $scenarios['editprofile'] = ['password', 'newPassword', 'verifyNewPassword'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required','message' => '请输入手机号!'],
            [['verycode'], 'required','message' => '请输入验证码!'],
            [['username'], 'match', 'pattern' => '/^1[3|4|5|7|8][0-9]{9}$/','message' => '请输入有效的手机号!'],
            ['username', 'unique', 'targetClass' => '\api\common\models\Member', 'message' => '该手机号已经存在!'],
            [['verycode'], function ($attribute, $params) {
                $verycode = Yii::$app->cache->get($this->username);
                if ($verycode !== $this->verycode) {
                    //$this->addError($attribute, '手机验证码不匹配！');
                }
            }],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'avatar', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            //注册资料场景
            [['username', 'password', 'verycode'], 'required', 'on' => 'register'], //必填
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

    /**
     * 修改资料
     * @author by lxhui
     *@param username 用户名
     * @param verycode 验证码
     * @version [2016-03-02]
     * @param array $params additional parameters
     * @desc 如果用户没有权限，应抛出一个ForbiddenHttpException异常
     */
    public function editProfile($id) {
        $user = User::findIdentity($id);
        if ($user) {
            if ($this->validate()) {
                echo(11);exit;
            } else {
                return false;
            }
        } else {
            $this->addError('username', '未找到该用户');
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
    /* 登录相关 */
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误!');
            }
        }
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }


}

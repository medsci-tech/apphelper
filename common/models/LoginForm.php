<<<<<<< HEAD
<?php

namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\AdminLog;
/**
 * Login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;
    private $_user;
    const loginfailnumber = 5;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha','message'=>'验证码错误!'], //验证码
            [['username', 'password'], 'validateLogin'],// 登录限制验证
        ];
    }
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我',
        ];
    }
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
     * Validates login access
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     * @author lxhui
     */
    public function validateLogin ($attribute, $params)
    {
        $cookies = Yii::$app->request->cookies; //获取cookie
        $loginSign = $cookies->getValue('loginSign');
        if($loginSign >= self::loginfailnumber)
            $this->addError($attribute, '当前登录错误已经超过最大限制!!');
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            if (array_key_exists("password",$this->errors)) // 记录用户或密码错误日志
            {
                $model = new AdminLog();
                $model->saves();
                $cookies = Yii::$app->request->cookies; //获取cookie
                $loginSign = $cookies->getValue('loginSign');
                $cookies = Yii::$app->response->cookies;
                // 在要发送的响应中添加一个新的cookie
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'loginSign',
                    'value' => $loginSign + 1,
                    'expire' => strtotime(date('Y-m-d 23:59:59'))
                ]));
            }
            return false;
        }
    }

    /**
     * Finds user by [[username]].
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
=======
<?php

namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\AdminLog;
/**
 * Login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;
    public $name;
    private $_user;
    const loginfailnumber = 5;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha','message'=>'验证码错误!'], //验证码
            [['username', 'password'], 'validateLogin'],// 登录限制验证
        ];
    }
    public function attributeLabels()
    {
        return [
            'name' => '应用App',
            'username' => '用户名',
            'password' => '密码',
            'rememberMe' => '记住我',
        ];
    }
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
     * Validates login access
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     * @author lxhui
     */
    public function validateLogin ($attribute, $params)
    {
        $cookies = Yii::$app->request->cookies; //获取cookie
        $loginSign = $cookies->getValue('loginSign');
        if($loginSign >= self::loginfailnumber)
            $this->addError($attribute, '当前登录错误已经超过最大限制!!');
    }
    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            if (array_key_exists("password",$this->errors)) // 记录用户或密码错误日志
            {
                $model = new AdminLog();
                $model->saves();
                $cookies = Yii::$app->request->cookies; //获取cookie
                $loginSign = $cookies->getValue('loginSign');
                $cookies = Yii::$app->response->cookies;
                // 在要发送的响应中添加一个新的cookie
                $cookies->add(new \yii\web\Cookie([
                    'name' => 'loginSign',
                    'value' => $loginSign + 1,
                    'expire' => strtotime(date('Y-m-d 23:59:59'))
                ]));
            }
            return false;
        }
    }

    /**
     * Finds user by [[username]].
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
>>>>>>> 3b7af9d316496c733d12671c8ef5c49f3d955176

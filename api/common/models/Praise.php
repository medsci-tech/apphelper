<?php
namespace api\common\models;
use yii\web\IdentityInterface;
use yii\base\Model;
class Praise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','uid'], 'required'],
            [['id'], 'required', 'message' => '评论id不能为空!'],
           // [['to_uid'], 'required', 'message' => '被点赞用户id不能为空!'],    
            ['uid', 'validatePraise'],
        ];
    }
    /**
     * Validates praise
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     */
    public function validatePraise($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user) {
                $this->addError($attribute, '您已经点赞过了!');
            }
        }
    }
    /**
     * Finds user by [[id,uid,to_uid]] 验证是否被点赞
     *
     * @return User|null
     */
    public function getUser()
    {
        return $this::find()->where(['id'=>$this->id,'uid'=>$this->uid])->one();
    }
    /**
     * save clicks 点赞
     * 
     * @return boolean whether the email was send
     */
    public function saves()
    {
        if ( !$this->validate()) {
            return false;
        }
        $this->created_at= time();
        $this->save();
        return true;
    }
    
}


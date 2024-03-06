<?php

namespace frontend\forms;

use common\forms\MemberForm;
use common\helpers\ArrayHelper;
use common\models\member\Member;

/**
 * Class SignupForm
 * @package frontend\models
 */
class SignupForm extends MemberForm
{
    public $username;
    public $email;
    public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['username', 'email'], 'trim'],
            [['email', 'username', 'password'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 20],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['password', 'string', 'min' => 6, 'max' => 20],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录账号',
            'password' => '登录密码',
            'email' => '电子邮箱',
        ];
    }

    /**
     * 注册
     *
     * @return Member|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        $user = new Member();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}

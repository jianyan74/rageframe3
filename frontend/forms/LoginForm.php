<?php

namespace frontend\forms;

use Yii;
use common\enums\MemberTypeEnum;
use common\models\member\Member;

/**
 * Class LoginForm
 * @package frontend\models
 */
class LoginForm extends \common\forms\LoginForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录账号',
            'password' => '登录密码',
            'rememberMe' => '记住我',
        ];
    }

    /**
     * 邮箱或账号登录
     *
     * @return Member|mixed|null
     */
    public function getUser()
    {
        if ($this->_user == false) {
            if (strpos($this->username, "@")) {
                $this->_user = Yii::$app->services->member->findByCondition([
                    'email' => $this->username,
                    'type' => MemberTypeEnum::MEMBER,
                ]);
            } else {
                $this->_user = Yii::$app->services->member->findByCondition([
                    'username' => $this->username,
                    'type' => MemberTypeEnum::MEMBER,
                ]);
            }
        }

        return $this->_user;
    }
}

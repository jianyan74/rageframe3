<?php

namespace api\modules\v1\forms;

use Yii;
use common\enums\MemberTypeEnum;
use common\enums\AccessTokenGroupEnum;

/**
 * Class LoginForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LoginForm extends \common\forms\LoginForm
{
    public $group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'group'], 'required'],
            ['password', 'validatePassword'],
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录帐号',
            'password' => '登录密码',
            'group' => '组别',
        ];
    }

    /**
     * 用户登录
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false) {
            // email 登录
            if (strpos($this->username, "@")) {
                $this->_user = Yii::$app->services->member->findByCondition([
                    'type' => MemberTypeEnum::MEMBER,
                    'email' => $this->username
                ]);
            } else {
                $this->_user = Yii::$app->services->member->findByCondition([
                    'type' => MemberTypeEnum::MEMBER,
                    'username' => $this->username
                ]);
            }
        }

        return $this->_user;
    }
}

<?php

namespace backend\forms;

use Yii;
use common\helpers\StringHelper;
use common\enums\MemberTypeEnum;
use common\helpers\ArrayHelper;
use common\enums\SubscriptionActionEnum;

/**
 * Class LoginForm
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LoginForm extends \common\forms\LoginForm
{
    /**
     * 校验验证码
     *
     * @var
     */
    public $verifyCode;

    /**
     * 默认登录失败3次显示验证码
     *
     * @var int
     */
    public $attempts = 3;

    /**
     * @var bool
     */
    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['password', 'validateIp'],
            ['verifyCode', 'captcha', 'on' => 'captchaRequired'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'rememberMe' => '记住我',
            'password' => '密码',
            'verifyCode' => '验证码',
        ];
    }

    /**
     * 验证ip地址是否正确
     *
     * @param $attribute
     * @throws \yii\base\InvalidConfigException
     */
    public function validateIp($attribute)
    {
        $allowIp = Yii::$app->services->config->backendConfig('sys_allow_ip');
        if (!empty($allowIp)) {
            $ipList = StringHelper::parseAttr($allowIp);
            if (!ArrayHelper::ipInArray(Yii::$app->request->userIP, $ipList)) {
                // 记录行为日志
                Yii::$app->services->actionLog->create('login', '限制IP登录', 0, [], false);

                $this->addError($attribute, '登录失败，请联系管理员');
            }
        }
    }

    /**
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Yii::$app->services->member->findByCondition([
                'username' => $this->username,
                'type' => MemberTypeEnum::MANAGER,
            ]);
        }

        return $this->_user;
    }

    /**
     * 验证码显示判断
     */
    public function loginCaptchaRequired()
    {
        if (Yii::$app->session->get('loginCaptchaRequired') >= $this->attempts) {
            $this->setScenario("captchaRequired");

            // 提醒
            Yii::$app->services->notify->sendRemind(0, SubscriptionActionEnum::ABNORMAL_LOGIN, 0, [
                'username' => $this->username,
                'attempts' => Yii::$app->session->get('loginCaptchaRequired')
            ]);
        }
    }

    /**
     * 登录
     *
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function login()
    {
        if ($this->validate() && Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0)) {
            Yii::$app->session->remove('loginCaptchaRequired');

            return true;
        }

        $counter = Yii::$app->session->get('loginCaptchaRequired') + 1;
        Yii::$app->session->set('loginCaptchaRequired', $counter);

        return false;
    }
}

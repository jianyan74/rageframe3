<?php

namespace merchant\forms;

use Yii;
use common\helpers\StringHelper;
use common\models\member\Member;
use common\enums\AppEnum;
use common\enums\AuditStatusEnum;
use common\models\merchant\Merchant;
use common\enums\MemberTypeEnum;
use common\helpers\ArrayHelper;
use common\enums\SubscriptionActionEnum;

/**
 * Class LoginForm
 * @package merchant\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LoginForm extends \common\forms\LoginForm
{
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
            ['password', 'validateMerchant'],
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
     * @param $attribute
     */
    public function validateMerchant($attribute)
    {
        /** @var Member $user */
        if ($user = $this->getUser()) {
            if (!($merchant = Merchant::findOne($user->merchant_id))) {
                $this->addError($attribute, '无法登陆请联系管理员');

                return false;
            }

            if ($merchant->audit_status == AuditStatusEnum::DISABLED) {
                $this->addError($attribute, '商户正在审核中,请等待');

                return false;
            }

            if (!Yii::$app->services->rbacAuthAssignment->findRoleIdSByUserIdAndAppId($user->id, AppEnum::MERCHANT)) {
                $this->addError($attribute, '未授权, 请联系管理员授权');

                return false;
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
                'type' => MemberTypeEnum::MERCHANT,
            ]);
        }

        return $this->_user;
    }

    /**
     * 验证码显示判断
     */
    public function loginCaptchaRequired()
    {
        if (Yii::$app->session->get('loginMerchantCaptchaRequired') >= $this->attempts) {
            $this->setScenario("captchaRequired");

            // 提醒
            Yii::$app->services->notify->sendRemind(0, SubscriptionActionEnum::ABNORMAL_LOGIN, 0, [
                'username' => $this->username,
                'attempts' => Yii::$app->session->get('loginMerchantCaptchaRequired')
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
            Yii::$app->session->remove('loginMerchantCaptchaRequired');

            return true;
        }

        $counter = Yii::$app->session->get('loginMerchantCaptchaRequired') + 1;
        Yii::$app->session->set('loginMerchantCaptchaRequired', $counter);

        return false;
    }
}

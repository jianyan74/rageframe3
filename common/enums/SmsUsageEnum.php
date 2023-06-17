<?php

namespace common\enums;

/**
 * Class SmsUsageEnum
 * @package addons\Merchants\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SmsUsageEnum extends BaseEnum
{
    const REGISTER = 'register';
    const LOGIN = 'login';
    const UP_PWD = 'up-pwd';
    const UP_PAY_PWD = 'up-pay-pwd';
    const RESET_MOBILE = 'reset-mobile';
    const BINDING_MOBILE = 'binding-mobile';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::REGISTER => '注册',
            self::LOGIN => '登录',
            self::UP_PWD => '修改密码',
            self::UP_PAY_PWD => '修改支付密码',
            self::RESET_MOBILE => '重置手机号码',
            self::BINDING_MOBILE => '绑定手机号码',
        ];
    }
}

<?php

namespace common\enums;

/**
 * Class MemberAuthOauthClientEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MemberAuthOauthClientEnum extends BaseEnum
{
    const WECHAT = 'wechat';
    const WECHAT_MP = 'wechatMp';
    const WECHAT_OP = 'wechatOp';
    const ALI_MP = 'aliMp';
    const APP_ANDROID = 'android';
    const APP_IOS = 'iOS';
    const BYTE_DANCE_MP = 'byteDanceMp';
    const APPLE = 'apple';
    const QQ = 'qq';
    const SINA = 'sina';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::WECHAT => '微信公众号',
            self::WECHAT_MP => '微信小程序',
            self::WECHAT_OP => '微信开放平台',
            self::ALI_MP => '支付宝小程序',
            self::BYTE_DANCE_MP => '字节跳动小程序',
            self::APPLE => 'apple',
            self::APP_ANDROID => '安卓推送',
            self::APP_IOS => 'iOS(苹果推送)',
            self::QQ => 'qq',
            self::SINA => '新浪',
        ];
    }
}

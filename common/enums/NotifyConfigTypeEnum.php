<?php

namespace common\enums;

/**
 * Class NotifyConfigTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyConfigTypeEnum extends BaseEnum
{
    const SYS = 'sys';
    const DING_TALK = 'dingTalk';
    const WECHAT_MP = 'wechatMp';
    const WECHAT_MINI = 'wechatMini';
    const SMS = 'sms';
    const EMAIL = 'email';
    const APP_PUSH = 'appPush';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::SYS => '系统提醒',
            self::DING_TALK => '钉钉提醒',
            self::WECHAT_MP => '微信消息',
            self::WECHAT_MINI => '微信小程序',
            self::SMS => '短信',
            self::EMAIL => '邮件',
            self::APP_PUSH => 'App推送',
        ];
    }
}

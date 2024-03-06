<?php

namespace common\enums;

/**
 * Class AccessTokenGroupEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenGroupEnum extends BaseEnum
{
    const DEFAULT = 'default';
    const PC = 'pc';
    const H5 = 'h5';

    // app + 推送
    const APP = 'app';
    const IOS = 'iOS';
    const ANDROID = 'android';

    // 公众号和小程序
    const WECHAT_MP = 'wechatMp';
    const WECHAT_MINI = 'wechatMini';
    const ALI_MINI = 'aliMini';
    const QQ_MINI = 'qqMini';
    const BAIDU_MINI = 'baiduMini';
    const DING_TALK_MINI = 'dingTalkMini';
    const BYTEDANCE_MINI = 'bytedanceMini';

    // 开放平台
    const WECHAT = 'wechat';
    const APPLE = 'apple';
    const QQ = 'qq';
    const SINA = 'sina'; // 新浪
    const GOOGLE = 'google';
    const FACEBOOK = 'facebook';

    // 其他
    const WEB_SOCKET = 'webSocket';
    const EXCEL_IMPORT = 'import';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '默认',
            self::IOS => 'iOS',
            self::ANDROID => 'Android',
            self::APP => 'App',
            self::H5 => 'H5',
            self::PC => 'PC',
            self::WECHAT => '微信', // 开放平台
            self::WECHAT_MP => '微信公众号',
            self::WECHAT_MINI => '微信小程序',
            self::ALI_MINI => '支付宝小程序',
            self::QQ_MINI => 'QQ小程序',
            self::BAIDU_MINI => '百度小程序',
            self::DING_TALK_MINI => '钉钉小程序',
            self::BYTEDANCE_MINI => '字节跳动小程序',
            self::WEB_SOCKET => 'WebSocket',
            self::EXCEL_IMPORT => '表格导入',
        ];
    }

    /**
     * @return string[]
     */
    public static function getThirdPartyMap(): array
    {
        return [
            self::IOS => 'iOS',
            self::ANDROID => 'android',
            self::APPLE => 'apple',
            self::WECHAT => '微信开放平台',
            self::WECHAT_MP => '微信公众号',
            self::WECHAT_MINI => '微信小程序',
            self::ALI_MINI => '支付宝小程序',
            self::QQ_MINI => 'QQ小程序',
            self::BAIDU_MINI => '百度小程序',
            self::DING_TALK_MINI => '钉钉小程序',
            self::BYTEDANCE_MINI => '字节跳动小程序',
        ];
    }
}

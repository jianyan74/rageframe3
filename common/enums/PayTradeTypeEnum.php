<?php

namespace common\enums;

/**
 * 支付场景类型
 *
 * Class PayTradeTypeEnum
 * @package common\enums
 */
class PayTradeTypeEnum extends BaseEnum
{
    const DEFAULT = 'default';

    /****************** 微信 *****************/
    const WECHAT_MP = 'mp';
    const WECHAT_MINI = 'mini';
    const WECHAT_APP = 'app';
    const WECHAT_SCAN = 'scan';
    const WECHAT_POS = 'pos';
    const WECHAT_WAP = 'wap';

    /****************** 支付宝 *****************/
    const ALI_WEB = 'web';
    const ALI_APP = 'app';
    const ALI_SCAN = 'scan';
    const ALI_POS = 'pos';
    const ALI_WAP = 'wap';

    /****************** 银联 *****************/
    const UNION_HTML = 'html';
    const UNION_APP = 'app';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public static function getWechatMap(): array
    {
        return [
            self::WECHAT_MP => '公众号',
            self::WECHAT_APP => 'app',
            self::WECHAT_SCAN => '二维码扫码',
            self::WECHAT_POS => '二维码收款',
            self::WECHAT_WAP => 'H5',
            self::WECHAT_MINI => '小程序',
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getAliMap(): array
    {
        return [
            self::ALI_WEB => '网页',
            self::ALI_APP => 'app',
            self::ALI_SCAN => '二维码扫码',
            self::ALI_WAP => '手机',
            self::ALI_POS => '二维码收款',
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getUnionMap(): array
    {
        return [
            self::UNION_HTML => '网页',
            self::UNION_APP => 'app',
        ];
    }
}

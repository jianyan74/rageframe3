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
    const WECHAT_WAP = 'wap';
    const WECHAT_APP = 'app';
    const WECHAT_MINI = 'mini';
    const WECHAT_SCAN = 'scan';
    const WECHAT_POS = 'pos';

    /****************** 支付宝 *****************/
    const ALI_WEB = 'web';
    const ALI_WAP = 'wap';
    const ALI_APP = 'app';
    const ALI_MINI = 'mini';
    const ALI_SCAN = 'scan';
    const ALI_POS = 'pos';

    /****************** 银联 *****************/
    const UNION_WEB = 'web';
    const UNION_WAP = 'wap';
    const UNION_SCAN = 'scan';
    const UNION_POS = 'pos';

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
            self::WECHAT_WAP => 'H5',
            self::WECHAT_APP => 'app',
            self::WECHAT_MINI => '小程序',
            self::WECHAT_SCAN => '二维码扫码',
            self::WECHAT_POS => '二维码收款',
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getAliMap(): array
    {
        return [
            self::ALI_WEB => '网页',
            self::ALI_WAP => '手机',
            self::ALI_APP => 'app',
            self::ALI_MINI => '小程序',
            self::ALI_SCAN => '二维码扫码',
            self::ALI_POS => '二维码收款',
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getUnionMap(): array
    {
        return [
            self::UNION_WEB => '网页',
            self::UNION_WAP => '手机',
            self::UNION_SCAN => '二维码扫码',
            self::UNION_POS => '二维码收款',
        ];
    }
}

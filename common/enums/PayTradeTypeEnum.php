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
    const WECHAT_JS = 'js';
    const WECHAT_APP = 'app';
    const WECHAT_NATIVE = 'native';
    const WECHAT_POS = 'pos';
    const WECHAT_M_WEB = 'mweb';
    const WECHAT_MINI_PROGRAM = 'mini_program';

    /****************** 支付宝 *****************/
    const ALI_PC = 'pc';
    const ALI_APP = 'app';
    const ALI_F2F = 'f2f';
    const ALI_WAP = 'wap';
    const ALI_CAPTURE = 'capture';

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
            self::WECHAT_JS => 'H5',
            self::WECHAT_APP => 'app',
            self::WECHAT_NATIVE => '扫码',
            self::WECHAT_POS => '刷卡',
            self::WECHAT_M_WEB => '手机',
            self::WECHAT_MINI_PROGRAM => '小程序',
        ];
    }

    /**
     * @return array|string[]
     */
    public static function getAliMap(): array
    {
        return [
            self::ALI_PC => 'PC',
            self::ALI_APP => 'app',
            self::ALI_F2F => '面对面二维码',
            self::ALI_WAP => '手机',
            self::ALI_CAPTURE => '面对面收款',
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
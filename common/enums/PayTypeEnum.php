<?php

namespace common\enums;

/**
 * 支付类型
 *
 * Class PayTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class PayTypeEnum extends BaseEnum
{
    const ON_LINE = 0;
    const USER_MONEY = 1;
    const CASH = 2;
    const PAY_ON_DELIVERY = 3;
    // 第三方支付
    const WECHAT = 100;
    const ALI = 101;
    const UNION = 102;
    const BYTE_DANCE = 103;

    // 信用卡
    const STRIPE = 200;

    // 其他
    const OFFLINE = 300;
    const INTEGRAL = 301;
    const BARGAIN = 302;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ON_LINE => '', // 在线支付
            self::WECHAT => '微信',
            self::ALI => '支付宝',
            self::UNION => '银联卡',
            self::PAY_ON_DELIVERY => '货到付款',
            self::USER_MONEY => '余额支付',
            self::CASH => '现金',
            self::BYTE_DANCE => '字节跳动',
            // 其他
            self::OFFLINE => '线下支付',
            self::INTEGRAL => '积分兑换',
            self::BARGAIN => '砍价',
            // 海外
            self::STRIPE => '信用卡支付(By Stripe)',
        ];
    }

    /**
     * 调用方法
     *
     * @param $type
     * @return mixed|string
     */
    public static function action($type)
    {
        $ations = [
            self::WECHAT => 'wechat',
            self::ALI => 'alipay',
            self::UNION => 'union',
            self::STRIPE => 'stripe',
        ];

        return $ations[$type] ?? '';
    }

    /**
     * @return array
     */
    public static function thirdParty()
    {
        return [
            self::USER_MONEY => '余额',
            self::WECHAT => '微信',
            self::ALI => '支付宝',
            self::UNION => '银联卡',
            self::STRIPE => '信用卡支付(By Stripe)',
        ];
    }
}

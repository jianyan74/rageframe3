<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class OrderStatusEnum
 * @package addons\WechatCapabilities\common\enums
 */
class OrderStatusEnum extends BaseEnum
{
    const NOT_PAY = 10;
    const WECHAT_PAY = 11; // 收银台支付完成（自动流转，对商家来说和10同等对待即可）
    const PAY = 20; // 待发货（已付款/用户已付尾款）
    const SHIPMENTS = 30;
    const SING = 100; // 完成
    const ACCOMPLISH = 100; // 完成
    const REPEAL = 250; // 用户主动取消/待付款超时取消/商家取消
    const CLOSE = 200; // 200 全部商品售后之后，订单取消

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::NOT_PAY => '未支付',
            self::PAY => '已付款',
            self::SHIPMENTS => '待收货',
            self::ACCOMPLISH => '完成',
            self::REPEAL => '已取消',
            self::CLOSE => '已取消',
        ];
    }
}

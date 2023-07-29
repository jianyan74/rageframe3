<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * 小程序接入状态
 *
 * Class AccessInfoItemEnum
 * @package addons\WechatMini\common\enums\video
 * @author jianyan74 <751393839@qq.com>
 */
class AccessInfoItemEnum extends BaseEnum
{
    const SPU = 6;
    const ORDER = 7;
    const COMPANY = 8;
    const AFTER_SALE = 9;
    const TEST = 10;
    const SUCCESS = 11;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::SPU => '完成spu接口',
            self::ORDER => '完成订单接口 ',
            self::COMPANY => '完成物流接口',
            self::AFTER_SALE => '完成售后接口 ',
            self::TEST => '测试完成',
            self::SUCCESS => '发版完成',
        ];
    }
}

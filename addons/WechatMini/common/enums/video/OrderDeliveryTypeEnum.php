<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class OrderDeliveryTypeEnum
 * @package addons\WechatCapabilities\common\enums
 */
class OrderDeliveryTypeEnum extends BaseEnum
{
    const LOGISTICS = 1;
    const VIRTUAL = 2;
    const LOCAL_DISTRIBUTION = 3;
    const PICKUP = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::LOGISTICS => '物流配送',
            self::PICKUP => '用户自提',
            self::VIRTUAL => '无需物流',
            self::LOCAL_DISTRIBUTION => '本地配送',
        ];
    }
}

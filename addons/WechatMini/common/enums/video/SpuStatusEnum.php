<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class ProductStatusEnum
 * @package addons\WechatCapabilities\common\enums
 */
class SpuStatusEnum extends BaseEnum
{
    const DEFAULT = 0;
    const REFUSE = 3;
    const PASS = 4;
    const PUT_AWAY = 5;
    const OUT = 11;
    const VIOLATION_OUT = 13;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '---', // 初始值
            self::REFUSE => '拒绝',
            self::PASS => '通过',
            self::PUT_AWAY => '上架',
            self::OUT => '自主下架',
            self::VIOLATION_OUT => '违规下架/风控系统下架',
        ];
    }
}

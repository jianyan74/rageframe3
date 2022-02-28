<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class SpecialTypeEnum
 * @package addons\Wechat\common\enums
 */
class SpecialTypeEnum extends BaseEnum
{
    const KEYWORD = 1;
    const MODULE = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::KEYWORD => '关键字',
            self::MODULE => '模块'
        ];
    }
}
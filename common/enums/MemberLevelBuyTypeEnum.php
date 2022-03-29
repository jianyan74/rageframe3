<?php

namespace common\enums;

/**
 * Class MemberLevelBuyTypeEnum
 * @package common\enums
 */
class MemberLevelBuyTypeEnum extends BaseEnum
{
    const GIVE = 1;
    const BUY = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::GIVE => '赠送',
            self::BUY => '购买',
        ];
    }
}
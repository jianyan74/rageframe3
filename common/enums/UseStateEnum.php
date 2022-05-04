<?php

namespace common\enums;

/**
 * Class UseStateEnum
 * @package common\enums
 */
class UseStateEnum extends BaseEnum
{
    const UNCLAIMED = 0;
    const GET = 1;
    const USE = 2;
    const PAST_DUE = 3;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::UNCLAIMED => '未领取',
            self::GET => '已领取',
            self::USE => '已使用',
            self::PAST_DUE => '已过期',
        ];
    }
}

<?php

namespace common\enums;

/**
 * Class WeekEnum
 * @package common\enums
 */
class WeekEnum extends BaseEnum
{
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    const SUNDAY = 0;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::MONDAY => '周一',
            self::TUESDAY => '周二',
            self::WEDNESDAY => '周三',
            self::THURSDAY => '周四',
            self::FRIDAY => '周五',
            self::SATURDAY => '周六',
            self::SUNDAY => '周日',
        ];
    }
}
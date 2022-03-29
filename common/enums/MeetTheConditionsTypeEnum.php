<?php

namespace common\enums;

/**
 * 满足条件
 *
 * Class MeetTheConditionsTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MeetTheConditionsTypeEnum extends BaseEnum
{
    const OR = 1;
    const AND = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::OR => '满足以下任意条件',
            self::AND => '满足以下全部条件',
        ];
    }
}

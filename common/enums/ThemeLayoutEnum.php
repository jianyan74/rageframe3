<?php

namespace common\enums;

/**
 * 主题布局
 *
 * Class ThemeLayoutEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ThemeLayoutEnum extends BaseEnum
{
    const DEFAULT = 'default';
    const SUBFIELD = 'subfield';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '默认',
            self::SUBFIELD => '分栏',
        ];
    }
}

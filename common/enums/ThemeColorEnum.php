<?php

namespace common\enums;

/**
 * 主题颜色
 *
 * Class ThemeColorEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ThemeColorEnum extends BaseEnum
{
    const BLACK = 'black';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::BLACK => '黑色',
        ];
    }
}

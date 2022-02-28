<?php

namespace common\enums;

/**
 * Class AddonTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AddonTypeEnum extends BaseEnum
{
    const DEFAULT = 'default';
    const ADDONS = 'addons';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '系统菜单',
            self::ADDONS => '插件菜单',
        ];
    }
}

<?php

namespace common\enums;

/**
 * Class OfficialEnum
 * @package common\enums
 */
class OfficialEnum extends BaseEnum
{
    const AUTHORITY = 'Authority';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::AUTHORITY => '官方插件',
        ];
    }
}
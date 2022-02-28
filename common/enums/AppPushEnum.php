<?php

namespace common\enums;

/**
 * Class AppPushEnum
 * @package common\enums
 */
class AppPushEnum extends BaseEnum
{
    const J_PUSH = 'jPush';
    const GE_TUI = 'geTui';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::J_PUSH => '极光推送',
            self::GE_TUI => '个推',
        ];
    }
}
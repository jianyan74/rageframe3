<?php

namespace common\enums;

/**
 * Class NotifyTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyTypeEnum extends BaseEnum
{
    // 消息类型
    const ANNOUNCE = 1; //公告
    const REMIND = 2; // 提醒
    const MESSAGE = 3; // 私信

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::ANNOUNCE => '公告',
            self::REMIND => '提醒',
            self::MESSAGE => '私信',
        ];
    }
}

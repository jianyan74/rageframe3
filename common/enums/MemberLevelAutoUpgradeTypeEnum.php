<?php

namespace common\enums;

/**
 * Class MemberLevelAutoUpgradeTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MemberLevelAutoUpgradeTypeEnum extends BaseEnum
{
    const CLOSE = 0;
    const AUTO = 1;
    const VIP_AUTO = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::CLOSE => '关闭升级',
            self::AUTO => '自动升级',
            self::VIP_AUTO => '会员自动升级',
        ];
    }
}

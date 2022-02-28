<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class FansFollowEnum
 * @package addons\Wechat\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class FansFollowEnum extends BaseEnum
{
    const ON = 1;
    const OFF = -1;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::ON  => '已关注',
            self::OFF => '未关注',
        ];
    }
}

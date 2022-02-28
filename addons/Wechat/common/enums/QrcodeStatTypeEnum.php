<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class QrcodeStatTypeEnum
 * @package addons\Wechat\common\enums
 */
class QrcodeStatTypeEnum extends BaseEnum
{
    const ATTENTION = 1;
    const SCAN = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::ATTENTION => '关注',
            self::SCAN => '扫描',
        ];
    }
}
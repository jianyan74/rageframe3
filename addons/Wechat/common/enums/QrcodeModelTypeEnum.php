<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class QrcodeModelTypeEnum
 * @package addons\Wechat\common\enums
 */
class QrcodeModelTypeEnum extends BaseEnum
{
    const TEM = 1;
    const PERPETUAL = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::TEM => '临时',
            self::PERPETUAL => '永久',
        ];
    }
}
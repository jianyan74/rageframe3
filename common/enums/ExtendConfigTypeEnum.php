<?php

namespace common\enums;

/**
 * 扩展配置类型
 *
 * Class ExtendConfigTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ExtendConfigTypeEnum extends BaseEnum
{
    const RECEIPT_PRINTER = 'receipt-printer';

    /**
     * @return array|string[]
     */
    public static function getMap(): array
    {
        return [
            self::RECEIPT_PRINTER => '小票打印机',
        ];
    }
}

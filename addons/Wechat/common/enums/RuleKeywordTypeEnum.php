<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class RuleKeywordTypeEnum
 * @package addons\Wechat\common\enums
 */
class RuleKeywordTypeEnum extends BaseEnum
{
    const MATCH = 1;
    const INCLUDE = 2;
    const REGULAR = 3;
    const TAKE = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MATCH => '直接匹配关键字',
            self::INCLUDE => '包含关键字',
            self::REGULAR => '正则表达式',
            self::TAKE => '直接接管',
        ];
    }
}

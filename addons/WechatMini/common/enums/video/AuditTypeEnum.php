<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class AuditTypeEnum
 * @package addons\WechatMini\common\enums\video
 * @author jianyan74 <751393839@qq.com>
 */
class AuditTypeEnum extends BaseEnum
{
    const CATE = 1;
    const BRAND = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::CATE => '类目',
            self::BRAND => '品牌',
        ];
    }
}

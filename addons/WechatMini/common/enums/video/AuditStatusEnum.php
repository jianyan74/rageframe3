<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class AuditStatusEnum
 * @package addons\WechatMini\common\enums\video
 * @author jianyan74 <751393839@qq.com>
 */
class AuditStatusEnum extends BaseEnum
{
    const AUDIT = 0;
    const PASS = 1;
    const REFUSE = 9;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::AUDIT => '审核中',
            self::PASS => '已通过',
            self::REFUSE => '拒绝',
        ];
    }
}

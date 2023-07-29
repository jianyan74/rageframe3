<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class ProductEditStatusEnum
 * @package addons\WechatCapabilities\common\enums
 */
class SpuEditStatusEnum extends BaseEnum
{
    const DEFAULT = 0;
    const EDIT = 1;
    const AUDIT = 2;
    const ERROR = 3;
    const SUCCESS = 4;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::DEFAULT => '---', // 初始值
            self::EDIT => '编辑中',
            self::AUDIT => '审核中',
            self::ERROR => '审核失败',
            self::SUCCESS => '审核成功',
        ];
    }
}

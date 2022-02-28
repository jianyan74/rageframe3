<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class AttachmentModelEnum
 * @package addons\Wechat\common\enums
 */
class AttachmentModelEnum extends BaseEnum
{
    const MODEL_PERM = 'perm';
    const MODEL_TEMP = 'temp';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MODEL_PERM => '永久素材',
            self::MODEL_TEMP => '临时素材',
        ];
    }
}
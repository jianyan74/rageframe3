<?php

namespace addons\TinyBlog\common\enums;

use common\enums\BaseEnum;

/**
 * Class SingleNameEnum
 * @package addons\TinyBlog\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SingleNameEnum extends BaseEnum
{
    const CONTACT_US = 'contact_us';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::CONTACT_US => '联系我们',
        ];
    }
}

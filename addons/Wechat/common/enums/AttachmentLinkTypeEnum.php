<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class AttachmentLinkTypeEnum
 * @package addons\Wechat\common\enums
 */
class AttachmentLinkTypeEnum extends BaseEnum
{
    /**
     * 微信图片前缀
     */
    const URL = 'http://mmbiz.qpic.cn';

    const WECHAT = 1;
    const LOCAL = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::WECHAT => '微信图文',
            self::LOCAL => '本地图文',
        ];
    }
}
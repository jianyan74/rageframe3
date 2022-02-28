<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class AttachmentTypeEnum
 * @package addons\Wechat\common\enums
 */
class AttachmentTypeEnum extends BaseEnum
{
    const NEWS = 'news';
    const TEXT = 'text';
    const VOICE = 'voice';
    const IMAGE = 'image';
    const CARD = 'card';
    const VIDEO = 'video';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::NEWS => '图文素材',
            self::IMAGE => '图片素材',
            // self::TEXT => '文字素材',
            self::VOICE => '音频素材',
            // self::CARD => '卡卷素材',
            self::VIDEO => '视频素材',
        ];
    }
}
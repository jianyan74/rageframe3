<?php

namespace common\enums;

/**
 * Class AttachmentUploadTypeEnum
 * @package common\enums
 */
class AttachmentUploadTypeEnum extends BaseEnum
{
    const IMAGES = 'images';
    const FILES = 'files';
    const VIDEOS = 'videos';
    const VOICES = 'voices';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::IMAGES => '图片',
            self::FILES => '文件',
            self::VIDEOS => '视频',
            self::VOICES => '音频',
        ];
    }
}
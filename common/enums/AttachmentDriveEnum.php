<?php

namespace common\enums;

/**
 * Class AttachmentDriveEnum
 * @package common\enums
 */
class AttachmentDriveEnum extends BaseEnum
{
    const LOCAL = 'local';
    const QINIU = 'qiniu';
    const OSS = 'oss';
    const OSS_DIRECT_PASSING = 'oss-direct-passing';
    const COS = 'cos';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::LOCAL => '本地',
            self::QINIU => '七牛',
            self::OSS => '阿里云OSS',
            self::COS => '腾讯云COS',
            // self::OSS_DIRECT_PASSING => 'OSS直传',
        ];
    }
}

<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class ServiceAgentTypeEnum
 * @package addons\WechatMini\common\enums\video
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceAgentTypeEnum extends BaseEnum
{
    const MINI = 0;
    const CUSTOM_PATH = 1;
    const MOBILE = 2;

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::MINI => '小程序客服',
            self::CUSTOM_PATH => '自定义客服路径',
            self::MOBILE => '联系电话',
        ];
    }

    /**
     * @param $data
     * @return string
     */
    public static function getText($data)
    {
        $text = [];
        foreach ($data as $datum) {
            $text[] = self::getValue($datum);
        }

        return implode(', ', $text);
    }
}

<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class RuleModuleEnum
 * @package addons\Wechat\common\enums
 */
class RuleModuleEnum extends BaseEnum
{
    /**
     * 模块类别
     */
    const TEXT = 'text';
    const NEWS = 'news';
    const MUSIC = 'music';
    const IMAGE = 'image';
    const VOICE = 'voice';
    const VIDEO = 'video';
    const ADDON = 'addon';
    const USER_API = 'user-api';
    const WX_CARD = 'wxcard';
    const DEFAULT = 'default';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::TEXT => '文字回复',
            self::IMAGE => '图片回复',
            self::NEWS => '图文回复',
            // self::MUSIC => '音乐回复',
            self::VOICE => '语音回复',
            self::VIDEO => '视频回复',
            // self::ADDON => '模块回复',
            self::USER_API => '自定义接口回复',
            // self::WX_CARD => '微信卡卷回复',
            // self::DEFAULT => '默认回复',
        ];
    }
}
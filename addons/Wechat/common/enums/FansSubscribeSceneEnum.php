<?php

namespace addons\Wechat\common\enums;

use common\enums\BaseEnum;

/**
 * Class FansSubscribeSceneEnum
 * @package addons\Wechat\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class FansSubscribeSceneEnum extends BaseEnum
{
    const ADD_SCENE_SEARCH = "ADD_SCENE_SEARCH";
    const ADD_SCENE_ACCOUNT_MIGRATION = "ADD_SCENE_ACCOUNT_MIGRATION";
    const ADD_SCENE_PROFILE_CARD = "ADD_SCENE_PROFILE_CARD";
    const ADD_SCENE_QR_CODE = "ADD_SCENE_QR_CODE";
    const ADD_SCENE_PROFILE_LINK = "ADD_SCENE_PROFILE_LINK";
    const ADD_SCENE_PROFILE_ITEM = "ADD_SCENE_PROFILE_ITEM";
    const ADD_SCENE_PAID = "ADD_SCENE_PAID";
    const ADD_SCENE_WECHAT_ADVERTISEMENT = "ADD_SCENE_WECHAT_ADVERTISEMENT";
    const ADD_SCENE_REPRINT = "ADD_SCENE_REPRINT";
    const ADD_SCENE_LIVESTREAM = "ADD_SCENE_LIVESTREAM";
    const ADD_SCENE_CHANNELS = "ADD_SCENE_CHANNELS";
    const ADD_SCENE_OTHERS = "ADD_SCENE_OTHERS";

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::ADD_SCENE_SEARCH  => '公众号搜索',
            self::ADD_SCENE_ACCOUNT_MIGRATION => '公众号迁移',
            self::ADD_SCENE_PROFILE_CARD => '名片分享',
            self::ADD_SCENE_QR_CODE => '扫描二维码',
            self::ADD_SCENE_PROFILE_LINK => '图文页内名称点击',
            self::ADD_SCENE_PROFILE_ITEM => '图文页右上角菜单',
            self::ADD_SCENE_PAID => '支付后关注',
            self::ADD_SCENE_WECHAT_ADVERTISEMENT => '微信广告',
            self::ADD_SCENE_REPRINT => '他人转载',
            self::ADD_SCENE_LIVESTREAM => '视频号直播',
            self::ADD_SCENE_CHANNELS => '视频号',
            self::ADD_SCENE_OTHERS => '其他',
        ];
    }
}

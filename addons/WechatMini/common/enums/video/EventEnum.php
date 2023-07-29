<?php

namespace addons\WechatMini\common\enums\video;

use common\enums\BaseEnum;

/**
 * Class EventEnum
 * @package addons\WechatCapabilities\common\enums
 */
class EventEnum extends BaseEnum
{
    const OPEN_PRODUCT_ACCOUNT_REGISTER = 'open_product_account_register';
    const OPEN_PRODUCT_SPU_AUDIT = 'open_product_spu_audit';
    const OPEN_PRODUCT_CATEGORY_AUDIT = 'open_product_category_audit';
    const OPEN_PRODUCT_BRAND_AUDIT = 'open_product_brand_audit';
    const OPEN_PRODUCT_SCENE_GROUP_AUDIT = 'open_product_scene_group_audit';
    const MINIPROGRAM_SHARER_BIND_STATUS_CHANGE = 'miniprogram_sharer_bind_status_change';
    const OPEN_PRODUCT_RECEIVE_COUPON = 'open_product_receive_coupon';

    /**
     * @return string[]
     */
    public static function getMap(): array
    {
        return [
            self::OPEN_PRODUCT_SPU_AUDIT => '商品审核结果/商品系统下架通知',
            self::OPEN_PRODUCT_ACCOUNT_REGISTER => '商家取消开通自定义组件',
            self::OPEN_PRODUCT_CATEGORY_AUDIT => '类目审核结果',
            self::OPEN_PRODUCT_BRAND_AUDIT => '品牌审核结果',
            self::OPEN_PRODUCT_SCENE_GROUP_AUDIT => '场景审核结果',
            self::MINIPROGRAM_SHARER_BIND_STATUS_CHANGE => '分享员绑定解绑通知',
            self::OPEN_PRODUCT_RECEIVE_COUPON => '用户领券通知',
        ];
    }
}

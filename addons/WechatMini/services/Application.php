<?php

namespace addons\WechatMini\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\WechatMini\services
 * @property ConfigService $config 默认配置
 *
 * 直播
 * @property \addons\WechatMini\services\live\LiveService $live 直播间
 * @property \addons\WechatMini\services\live\GoodsService $liveGoods 商品
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'config' => ConfigService::class,
        //***************************** 小程序直播 *****************************//
        'live' => '\addons\WechatMini\services\live\LiveService',
        'liveGoods' => '\addons\WechatMini\services\live\GoodsService',
    ];
}

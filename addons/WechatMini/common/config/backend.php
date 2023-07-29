<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fas fa-podcast',
            'pattern' => ['blank'], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
        ],
        // 子模块配置
        'modules' => [
            // 直播间
            'live' => [
                'class' => 'addons\WechatMini\merchant\modules\live\Module',
            ],
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '直播间',
            'name' => 'live/live/index',
            'icon' => 'fa fa-sliders-h'
        ],
        [
            'title' => '小程序配置',
            'name' => 'config/index',
            'icon' => 'fa fa-cog'
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [

    ],
];

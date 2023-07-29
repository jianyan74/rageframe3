<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fas fa-wind',
            'sort' => 2000, // 自定义排序
            'pattern' => ['b2c', 'b2b2c'], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
        ],
        // 子模块配置
        'modules' => [
            // 微信
            'wechat' => [
                'class' => 'common\components\BaseAddonModule',
                'name' => 'Wechat',
                'app_id' => 'merchant',
            ],
            // 微信小程序
            'wechat-mini' => [
                'class' => 'common\components\BaseAddonModule',
                'name' => 'WechatMini',
                'app_id' => 'merchant',
            ],
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '微信公众号',
            'name' => 'wechat',
            'icon' => 'fa fa-comments',
            'child' => [
                [
                    'title' => '增强功能',
                    'name' => 'function',
                    'child' => [
                        [
                            'title' => '自动回复',
                            'name' => 'wechat/rule/index',
                        ],
                        [
                            'title' => '自定义菜单',
                            'name' => 'wechat/menu/index',
                        ],
                        [
                            'title' => '二维码',
                            'name' => 'wechat/qrcode/index',
                        ],
                    ],
                ],
                [
                    'title' => '粉丝管理',
                    'name' => 'wechat/fans',
                    'child' => [
                        [
                            'title' => '粉丝列表',
                            'name' => 'wechat/fans/index',
                        ],
                        [
                            'title' => '粉丝标签',
                            'name' => 'wechat/fans-tags/index',
                        ],
                    ],
                ],
                [
                    'title' => '素材库',
                    'name' => 'wechat/attachment/index',
                ],
                [
                    'title' => '历史消息',
                    'name' => 'wechat/message-history/index',
                ],
                [
                    'title' => '定时群发',
                    'name' => 'wechat/mass-record/index',
                ],
                [
                    'title' => '数据统计',
                    'name' => 'wechat/stat',
                    'child' => [
                        [
                            'title' => '粉丝关注统计',
                            'name' => 'wechat/stat/fans-follow',
                        ],
                        [
                            'title' => '回复规则使用量',
                            'name' => 'wechat/stat/rule',
                        ],
                        [
                            'title' => '关键字命中规则',
                            'name' => 'wechat/stat/rule-keyword',
                        ],
                    ],
                ],
                [
                    'title' => '微信配置',
                    'name' => 'wechat/config/index',
                    'icon' => 'fa fa-cog',
                    'pattern' => ['b2c', 'b2b2c'], // 可见模式
                ],
            ]
        ],
        [
            'title' => '微信小程序',
            'name' => 'wechat-mini',
            'icon' => 'fas fa-podcast',
            'child' => [
                [
                    'title' => '直播间',
                    'name' => 'wechat-mini/live/live/index',
                ],
                [
                    'title' => '小程序配置',
                    'name' => 'wechat-mini/config/index',
                ],
            ]
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '所有权限',
            'name' => '*',
        ],
        [
            'title' => '微信公众号',
            'name' => 'wechat/*',
        ],
        [
            'title' => '微信小程序',
            'name' => 'wechat-mini/*',
        ],
    ],
];

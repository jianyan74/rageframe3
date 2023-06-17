<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-comments',
            'sort' => 11, // 自定义排序
            'pattern' => ['blank']
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '增强功能',
            'name' => 'function',
            'icon' => 'fa fa-dharmachakra',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
            'child' => [
                [
                    'title' => '自动回复',
                    'name' => 'rule/index',
                ],
                [
                    'title' => '自定义菜单',
                    'name' => 'menu/index',
                ],
                [
                    'title' => '二维码',
                    'name' => 'qrcode/index',
                ],
            ],
        ],
        [
            'title' => '粉丝管理',
            'name' => 'fans',
            'icon' => 'fa fa-user-friends',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
            'child' => [
                [
                    'title' => '粉丝列表',
                    'name' => 'fans/index',
                ],
                [
                    'title' => '粉丝标签',
                    'name' => 'fans-tags/index',
                ],
            ],
        ],
        [
            'title' => '素材库',
            'name' => 'attachment/index',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
            'icon' => 'fa fa-file',
        ],
        [
            'title' => '历史消息',
            'name' => 'message-history/index',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
            'icon' => 'fa fa-comments',
        ],
        [
            'title' => '定时群发',
            'name' => 'mass-record/index',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
            'icon' => 'fa fa-share',
        ],
        [
            'title' => '数据统计',
            'name' => 'dataStatistics',
            'icon' => 'fa fa-chart-pie',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
            'child' => [
                [
                    'title' => '粉丝关注统计',
                    'name' => 'stat/fans-follow',
                ],
                [
                    'title' => '回复规则使用量',
                    'name' => 'stat/rule',
                ],
                [
                    'title' => '关键字命中规则',
                    'name' => 'stat/rule-keyword',
                ],
            ],
        ],
        [
            'title' => '微信配置',
            'name' => 'config/index',
            'icon' => 'fa fa-cog',
            'pattern' => ['b2c', 'b2b2c'], // 可见模式
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [

    ],
];

<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-comments',
            'sort' => 11, // 自定义排序
            'pattern' => ['saas'], // 可见模式
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
            'pattern' => ['saas'], // 可见模式
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
                    'title' => '二维码/转化链接',
                    'name' => 'qrcode/index',
                ],
            ],
        ],
        [
            'title' => '粉丝管理',
            'name' => 'fans',
            'icon' => 'fa fa-user-friends',
            'pattern' => ['saas'], // 可见模式
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
            'icon' => 'fa fa-file',
            'pattern' => ['saas'], // 可见模式
        ],
        [
            'title' => '历史消息',
            'name' => 'message-history/index',
            'icon' => 'fa fa-comments',
            'pattern' => ['saas'], // 可见模式
        ],
        [
            'title' => '定时群发',
            'name' => 'mass-record/index',
            'icon' => 'fa fa-share',
            'pattern' => ['saas'], // 可见模式
        ],
        [
            'title' => '数据统计',
            'name' => 'dataStatistics',
            'icon' => 'fa fa-chart-pie',
            'pattern' => ['saas'], // 可见模式
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
            'title' => '参数配置',
            'name' => 'setting/history-stat',
            'icon' => 'fa fa-cog',
            'pattern' => ['saas'], // 可见模式
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '所有权限',
            'name' => '*',
        ],
    ],
];

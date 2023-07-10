<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-user',
            'sort' => 0, // 自定义排序
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '会员管理',
            'name' => 'indexMember',
            'icon' => 'fa fa-user',
            'child' => [
                [
                    'title' => '会员信息',
                    'name' => 'member/index',
                ],
                [
                    'title' => '会员等级',
                    'name' => 'level/index',
                    'pattern' => ['b2c', 'b2b2c'],
                ],
                [
                    'title' => '会员标签',
                    'name' => 'tag/index',
                    'pattern' => ['b2c', 'b2b2c'],
                ],
                [
                    'title' => '第三方授权',
                    'name' => 'auth/index',
                    'pattern' => ['b2c', 'b2b2c'],
                ],
                [
                    'title' => '黑名单',
                    'name' => 'blacklist/index',
                    'pattern' => ['b2c', 'b2b2c'],
                ],
                [
                    'title' => '会员注销',
                    'name' => 'cancel/index',
                    'pattern' => ['b2c', 'b2b2c'],
                ]
            ]
        ],
        [
            'title' => '会员日志',
            'name' => 'memberCreditsLog',
            'icon' => 'fa fa-file-alt',
            'child' => [
                [
                    'title' => '余额日志',
                    'name' => 'credits-log/money',
                ],
                [
                    'title' => '消费日志',
                    'name' => 'credits-log/consume',
                ],
                [
                    'title' => '积分日志',
                    'name' => 'credits-log/integral',
                ],
                [
                    'title' => '成长值日志',
                    'name' => 'credits-log/growth',
                ],
            ]
        ]
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '所有权限',
            'name' => '*',
        ],
    ],
];

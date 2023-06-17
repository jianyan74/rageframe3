<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-puzzle-piece',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '数据迁移生成',
            'name' => 'migrate/index',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '二维码生成',
            'name' => 'qr/index',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '省市区爬虫',
            'name' => 'province-job/index',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '数据列表',
            'name' => 'data-base/backups',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '时间戳转换',
            'name' => 'timestamp/index',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => '系统探针',
            'name' => 'system/probe',
            'icon' => '',
            'params' => [
            ],
        ],
        [
            'title' => 'PHP信息',
            'name' => 'php-info/index',
            'icon' => '',
            'params' => [
            ],
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

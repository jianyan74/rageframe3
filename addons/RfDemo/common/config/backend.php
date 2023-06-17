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
        'modules' => [],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => 'Curd',
            'name' => 'curd/index',
            'icon' => '',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
            'params' => [],
            'child' => [],
        ],
        [
            'title' => 'TreeGrid 分类',
            'name' => 'cate-tree-grid/index',
            'icon' => 'fa fa-tree',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
            'params' => [],
            'child' => [],
        ],
        [
            'title' => 'JsTree 分类',
            'name' => 'cate-js-tree/index',
            'icon' => 'fa fa-stream',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
            'params' => [],
            'child' => [],
        ],
        [
            'title' => '参数设置',
            'name' => 'setting/display',
            'icon' => 'fa fa-cog',
            'pattern' => [], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
            'params' => [],
            'child' => [],
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

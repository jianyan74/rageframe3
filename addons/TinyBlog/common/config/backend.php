<?php

return [

    // ----------------------- 参数配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'default', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-blog',
            'pattern' => ['b2c', 'b2b2c'], // 可见开发模式 b2c、b2b2c、saas 不填默认全部可见, 可设置为 blank 为全部不可见
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '文章管理',
            'name' => 'article/index',
            'icon' => 'fa fa-newspaper'
        ],
        [
            'title' => '文章分类',
            'name' => 'cate/index',
            'icon' => 'fa fa-list'
        ],
        [
            'title' => '文章标签',
            'name' => 'tag/index',
            'icon' => 'fa fa-tags'
        ],
        [
            'title' => '单页管理',
            'name' => 'single/index',
            'icon' => 'fa fa-puzzle-piece'
        ],
        [
            'title' => '广告图',
            'name' => 'adv/index',
            'icon' => 'fa fa-image'
        ],
        [
            'title' => '友情链接',
            'name' => 'friendly-link/index',
            'icon' => 'fa fa-link'
        ],
        [
            'title' => '回收站',
            'name' => 'article/recycle',
            'icon' => 'fa fa-recycle'
        ],
        [
            'title' => '参数设置',
            'name' => 'setting/display',
            'icon' => 'fa fa-cog',
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

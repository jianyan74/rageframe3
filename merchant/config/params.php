<?php

return [
    'adminEmail' => 'admin@example.com',
    'adminAcronym' => 'RF',
    'adminTitle' => 'RF商户端',
    'adminDefaultHomePage' => ['main/home'], // 默认主页

    /** ------ 总管理员配置 ------ **/
    'adminAccount' => [],// 系统管理员账号id，开发可以设置为 1 (总管理员)
    'isMobile' => false, // 手机访问

    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.except.code' => [404], // 不记录的code

    /** ------ 当前商户 ------ **/
    'merchant' => [],

    // token有效期 默认 2 小时
    'user.accessTokenExpire' => 2 * 60 * 60,

    /**
     * 不需要验证的路由全称
     *
     * 注意: 前面以绝对路径/为开头
     */
    'noAuthRoute' => [
        '/main/index',// 系统主页
        '/main/home',// 系统首页
        '/personal/index',// 个人信息
        '/personal/update-password',// 修改密码
        '/theme/update', // 主题修改
    ],
];

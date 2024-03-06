<?php
return [
    'adminEmail' => '751393839@qq.com',
    'adminAcronym' => 'RF',
    'adminTitle' => 'RageFrame',
    'adminDefaultHomePage' => ['main/home'], // 默认主页

    'isMobile' => false, // 手机访问

    /** ------ 开发者信息 ------ **/
    'exploitDeveloper' => '简言',
    'exploitFullName' => 'RageFrame 应用开发引擎',
    'exploitOfficialWebsite' => '<a href="http://www.rageframe.com" target="_blank">www.rageframe.com</a>',
    'exploitGitHub' => '<a href="https://github.com/jianyan74/rageframe3" target="_blank">https://github.com/jianyan74/rageframe3</a>',

    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.except.code' => [404], // 不记录的code

    /**
     * 不需要验证的路由全称
     *
     * 注意: 前面以绝对路径/为开头
     */
    'noAuthRoute' => [
        '/main/index', // 系统主页
        '/main/home', // 系统首页
        '/main/clear-cache', // 清理缓存
        '/main/member-between-count',
        '/main/member-credits-log-between-count',
        '/personal/index',// 个人信息
        '/personal/update-password',// 修改密码
        '/theme/update', // 主题修改
    ],
];

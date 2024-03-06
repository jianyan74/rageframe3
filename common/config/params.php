<?php

return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,

    /** ------ 系统授权秘钥 ------ **/
    'secret_key' => 'open_source',

    /** ------ 总管理员配置 ------ **/
    // 系统管理员账号id
    'adminAccount' => [1],
    // 无需授权的路由别名
    'noAuthRoute' => [],
    // 开发模式
    'devPattern' => 'b2c',
    // 多店铺 (saas、连锁)
    'multiShop' => false,
    // 请求全局唯一ID
    'uuid' => '',
    // 主题布局
    'theme' => [
        'layout' => 'default',
        'color' => 'black',
    ],
    // 全局缓存过期时间 (建议用 redis 缓存)
    'cacheExpirationTime' => [
        'default' => null, // 常用数据缓存
        'common' => null, // 5s 常用数据缓存
        'ordinary' => null, // 60s 一般数据缓存
        'rarely' => null, // 360s 不常用数据缓存
    ],
    // 真实 app id
    'realAppId' => '',
    // 判断默认是否在插件内
    'inAddon' => false,

    /** ------ websocket (建议本地配置) ------ **/
    'websocket' => [
        // 守护进程
        'daemonize' => true,
        // ws 端口
        'port' => 9503,
        // ws: false; wss: true
        'ssl' => false,
        // 更多ssl选项请参考手册 http://php.net/manual/zh/context.ssl.php
        'sslConfig' => [
            // 请使用绝对路径
            'local_cert' => 'path/server.pem', // 也可以是crt文件
            'local_pk' => 'path/server.key',
            'verify_peer' => false,
            'allow_self_signed' => false, // 如果是自签名证书需要开启此选项
        ],
        // ---------------- gateway worker ----------------
        // 如果一个服务器需要跑多个服务，改端口的同时需要改大一点，避免启动冲突
        'gatewayStartPort' => 2900,
        // 负责网络 IO 进程数量; 最好设置为 CPU 核数
        'gatewayCount' => 2,
        // 业务处理进程数量; 根据业务是否有阻塞式 IO 设置进程数为CPU核数的 1倍 - 3倍
        'businessWorkerCount' => 4,
        // 集群启动状态 true: 开启; false: 关闭
        'cluster' => false,
        // 集群配置 (gateway-worker 方式启动默认引用)
        'clusterConfig' => [
            'master' => true, // 主服务器 true: 是; false: 否; 一台为主服务器(register 和 gateway 服务)即可
            'registerIp' => '127.0.0.1', // 主服务器 IP
            'registerPort' => 1238,  // 主服务器端口
            'gatewayLanIp' => '127.0.0.1', // 分布式时候请使用内网 IP (一般不需要改)
            'secretKey' => '', // 秘钥, 分布式部署必须设置
        ],
    ],

    // 记录上传到表里
    'fileWriteTable' => true,
    // 百度编辑器默认上传驱动
    'UEditorUploadDrive' => 'local',
    // 全局上传配置
    'uploadConfig' => [
        // 图片
        'images' => [
            'originalName' => false, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 10,// 图片最大上传大小,默认10M
            'extensions' => ["png", "jpg", "jpeg", "gif", "bmp"],// 可上传图片后缀不填写即为不限
            'path' => 'images/', // 图片创建路径
            'subName' => 'Y/m/d', // 图片上传子目录规则
            'prefix' => 'image_', // 图片名称前缀
            'mimeTypes' => 'image/*', // 媒体类型
            'compress' => false, // 是否开启压缩
            'compressibility' => [ // 100不压缩 值越大越清晰 注意先后顺序
                1024 * 100 => 100, // 0 - 100k 内不压缩
                1024 * 1024 => 30, // 100k - 1M 区间压缩质量到30
                1024 * 1024 * 2  => 20, // 1M - 2M 区间压缩质量到20
                1024 * 1024 * 1024  => 10, // 2M - 1G 区间压缩质量到20
            ],
        ],
        // 视频
        'videos' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 50,// 最大上传大小,默认50M
            'extensions' => ['mp4', 'mp3'],// 可上传文件后缀不填写即为不限
            'path' => 'videos/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'video_',// 名称前缀
            'mimeTypes' => 'video/*', // 媒体类型
        ],
        // 语音
        'voices' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 30,// 最大上传大小,默认30M
            'extensions' => ['amr', 'mp3'],// 可上传文件后缀不填写即为不限
            'path' => 'voice/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'voice_',// 名称前缀
            'mimeTypes' => 'audio/*', // 媒体类型
        ],
        // 文件
        'files' => [
            'originalName' => true, // 是否保留原名
            'fullPath' => true, // 是否开启返回完整的文件路径
            'takeOverUrl' => '', // 配置后，接管所有的上传地址
            'drive' => 'local', // 默认本地 可修改 qiniu/oss/cos 上传
            'md5Verify' => true, // md5 校验
            'maxSize' => 1024 * 1024 * 150,// 最大上传大小,默认150M
            'extensions' => [],// 可上传文件后缀不填写即为不限
            'path' => 'files/',// 创建路径
            'subName' => 'Y/m/d',// 上传子目录规则
            'prefix' => 'file_',// 名称前缀
            'mimeTypes' => '*', // 媒体类型
            'blacklist' => [ // 文件后缀黑名单
                'php', 'php5', 'php4', 'php3', 'php2', 'php1',
                'java', 'asp', 'jsp', 'jspa', 'javac',
                'py', 'pl', 'rb', 'sh', 'ini', 'svg', 'html', 'jtml','phtml','pht', 'js'
            ],
        ],
        // 缩略图
        'thumb' => [
            'path' => 'thumb/',// 图片创建路径
        ],
    ],

    /** ------ 微信配置 ------ **/

    // 微信配置 具体可参考EasyWechat
    'wechatConfig' => [],
    // 微信支付配置 具体可参考EasyWechat
    'wechatPaymentConfig' => [],
    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [],
    // 微信开放平台第三方平台配置 具体可参考EasyWechat
    'wechatOpenPlatformConfig' => [],
    // 微信企业微信配置 具体可参考EasyWechat
    'wechatWorkConfig' => [],
    // 微信企业微信开放平台 具体可参考EasyWechat
    'wechatOpenWorkConfig' => [],

    /** ------ 插件类型 ------ **/
    'addonsGroup' => [
        'plug' => [
            'name' => 'plug',
            'title' => '功能扩展',
            'icon' => 'fa fa-feather',
        ],
        'business' => [
            'name' => 'business',
            'title' => '主要业务',
            'icon' => 'fa fa-lemon',
        ],
        'customer' => [
            'name' => 'customer',
            'title' => '客户关系',
            'icon' => 'fa fa-user-friends',
        ],
        'activity' => [
            'name' => 'activity',
            'title' => '营销及活动',
            'icon' => 'fa fa-palette',
        ],
        'services' => [
            'name' => 'services',
            'title' => '常用服务及工具',
            'icon' => 'fa fa-magnet',
        ],
        'biz' => [
            'name' => 'biz',
            'title' => '行业解决方案',
            'icon' => 'fa fa-gem',
        ],
        'h5game' => [
            'name' => 'h5game',
            'title' => '小游戏',
            'icon' => 'fa fa-gamepad',
        ],
    ],

    'bsVersion' => '4.x',
    'bsDependencyEnabled' => false,
];

<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'main', // 默认控制器
    'bootstrap' => ['log'],
    'modules' => [
        /** ------ 公用模块 ------ **/
        'common' => [
            'class' => 'backend\modules\common\Module',
        ],
        /** ------ oauth2 ------ **/
        'oauth2' => [
            'class' => 'backend\modules\oauth2\Module',
        ],
        /** ------ 扩展模块 ------ **/
        'extend' => [
            'class' => 'backend\modules\extend\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\member\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'on afterLogin' => function ($event) {
                Yii::$app->services->member->lastLogin($event->identity);
            },
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'timeout' => 86400,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m/d') . '.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'assetManager' => [
            // 'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [],
                    'sourcePath' => null,
                ],
                'yii\bootstrap4\BootstrapAsset' => [
                    'css' => [],  // 去除 bootstrap.css
                    'sourcePath' => null,
                ],
                'yii\bootstrap4\BootstrapPluginAsset' => [
                    'js' => [],  // 去除 bootstrap.js
                    'sourcePath' => null,
                ],
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                Yii::$app->services->log->record($event->sender);
            },
        ],
    ],
    'container' => [
        'definitions' => [
            \yii\widgets\LinkPager::class => \yii\bootstrap4\LinkPager::class,
            'yii\bootstrap4\LinkPager' => [
                'options' => [
                    'class' => ['clearfix'],
                ],
                'listOptions' => [
                    'class' => ['pagination float-right hide'],
                ],
                'nextPageLabel' => '<i class="icon ion-ios-arrow-right"></i>',
                'prevPageLabel' => '<i class="icon ion-ios-arrow-left"></i>',
                'lastPageLabel' => '<i class="icon ion-ios-arrow-right"></i><i class="icon ion-ios-arrow-right"></i>',
                'firstPageLabel' => '<i class="icon ion-ios-arrow-left"></i><i class="icon ion-ios-arrow-left"></i>',
            ]
        ],
        'singletons' => [
            // 依赖注入容器单例配置
        ]
    ],
    'controllerMap' => [
        'files' => 'common\widgets\webuploader\FilesController', // 文件上传公共控制器
        'ueditor' => 'common\widgets\ueditor\UEditorController', // 百度编辑器
        'map' => 'common\widgets\map\MapController', // 经纬度选择
        'map-overlay' => 'common\widgets\map\MapOverlayController', // 地图范围设置
        'cropper' => 'common\widgets\cropper\CropperController', // 图片裁剪
        'provinces' => 'common\controllers\ProvincesController', // 省市区
        'notify' => 'common\widgets\notify\NotifyController', // 消息
    ],
    'as cors' => [
        'class' => \yii\filters\Cors::class,
    ],
    'params' => $params,
];

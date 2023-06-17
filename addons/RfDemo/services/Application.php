<?php

namespace addons\RfDemo\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\RfDemo\services
 * @property ConfigService $config 默认配置
 * @property CateService $cate 分类
 * @property CurdMapService $curdMap 地图范围
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'config' => ConfigService::class,
        'cate' => CateService::class,
        'curdMap' => CurdMapService::class,
    ];
}

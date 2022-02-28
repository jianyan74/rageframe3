<?php

namespace addons\Authority\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\Authority\services
 * @property ConfigService $config 默认配置
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        'config' => ConfigService::class
    ];
}

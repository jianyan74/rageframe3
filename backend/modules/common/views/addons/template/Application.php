<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>\services;

use common\components\Service;

/**
 * Class Application
 *
 * @package addons\<?= $model->name;?>\services
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

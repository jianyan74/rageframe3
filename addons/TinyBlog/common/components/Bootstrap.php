<?php

namespace addons\TinyBlog\common\components;

use Yii;
use common\enums\AppEnum;
use common\interfaces\AddonWidget;

/**
 * Bootstrap
 *
 * Class Bootstrap
 * @package addons\TinyBlog\common\config
 */
class Bootstrap implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     */
    public function run($addon)
    {
        if (Yii::$app->id == AppEnum::FRONTEND) {
            Yii::$app->errorHandler->errorAction = '/tiny-blog/site/error';
        }
    }
}

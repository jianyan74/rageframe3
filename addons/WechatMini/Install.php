<?php

namespace addons\WechatMini;

use Yii;
use yii\db\Migration;
use common\helpers\MigrateHelper;
use common\interfaces\AddonWidget;

/**
 * 安装
 *
 * Class Install
 * @package addons\WechatMini
 */
class Install extends Migration implements AddonWidget
{
    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function run($addon)
    {
        MigrateHelper::upByPath([
            '@addons/WechatMini/console/migrations/'
        ]);
    }
}

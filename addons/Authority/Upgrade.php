<?php

namespace addons\Authority;

use Yii;
use common\components\Migration;
use common\interfaces\AddonWidget;

/**
 * 升级数据库
 *
 * Class Upgrade
 * @package addons\Authority
 */
class Upgrade extends Migration implements AddonWidget
{
    /**
     * @var array
     */
    public $versions = [
        '3.0.0', // 默认版本
        '3.0.3', '3.0.10',
    ];

    /**
    * @param $addon
    * @return mixed|void
    * @throws \yii\db\Exception
    */
    public function run($addon)
    {
        switch ($addon->version) {
            case '3.0.10' :
                break;
            case '3.0.3' :
                break;
            case '3.0.0' :
                break;
        }
    }
}

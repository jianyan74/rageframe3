<?php

namespace addons\Authority;

use Yii;
use yii\db\Exception;
use common\enums\AppEnum;
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
        '3.0.3', '3.0.10', '3.0.12', '3.0.18',
    ];

    /**
     * @param $addon
     * @return mixed|void
     * @throws Exception
     */
    public function run($addon)
    {
        switch ($addon->version) {
            case '3.0.18' :
                Yii::$app->services->config->findSaveByName('map_amap_code', AppEnum::BACKEND, [
                    'title' => 'Web端(Js Api)安全秘钥',
                    'name' => 'map_amap_code',
                    'app_id' => 'backend',
                    'type' => 'text',
                    'cate_id' => '32',
                    'extra' => '',
                    'remark' => '地图选择',
                    'is_hide_remark' => '0',
                    'default_value' => '',
                    'sort' => '1',
                    'status' => '1',
                ]);
                break;
            case '3.0.0' :
                break;
        }
    }
}

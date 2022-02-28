<?php

namespace addons\RfDemo\common\forms;

use addons\RfDemo\common\models\Curd;
use common\helpers\ArrayHelper;

/**
 * Class CurdForm
 * @package addons\RfDemo\common\forms
 */
class CurdForm extends Curd
{
    public $longitude_and_latitude = [];

    /**
     * @return array|string[]
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'longitude_and_latitude' => '地图位置选择',
        ]);
    }
}
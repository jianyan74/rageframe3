<?php

namespace addons\Authority\services;

use common\components\BaseAddonConfigService;
use addons\Authority\common\models\SettingForm;

/**
* Class ConfigService
*
* @package addons\Authority\services
*/
class ConfigService extends BaseAddonConfigService
{
    /**
    * @var string
    */
    public $addonName = "Authority";

    /**
    * @var SettingForm
    */
    public $settingForm = SettingForm::class;
}

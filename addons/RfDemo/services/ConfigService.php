<?php

namespace addons\RfDemo\services;

use common\components\BaseAddonConfigService;
use addons\RfDemo\common\models\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\RfDemo\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "RfDemo";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

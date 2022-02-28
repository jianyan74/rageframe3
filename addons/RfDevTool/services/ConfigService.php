<?php

namespace addons\RfDevTool\services;

use common\components\BaseAddonConfigService;
use addons\RfDevTool\common\models\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\RfDevTool\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "RfDevTool";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

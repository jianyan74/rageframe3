<?php

namespace addons\TinyChannel\services;

use common\components\BaseAddonConfigService;
use addons\TinyChannel\common\forms\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\TinyChannel\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "TinyChannel";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

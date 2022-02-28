<?php

namespace addons\Member\services;

use common\components\BaseAddonConfigService;
use addons\Member\common\models\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\Member\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "Member";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

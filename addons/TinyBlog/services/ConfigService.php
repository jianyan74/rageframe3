<?php

namespace addons\TinyBlog\services;

use common\components\BaseAddonConfigService;
use addons\TinyBlog\common\forms\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\TinyBlog\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "TinyBlog";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

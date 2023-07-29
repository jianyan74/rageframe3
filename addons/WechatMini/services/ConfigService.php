<?php

namespace addons\WechatMini\services;

use common\components\BaseAddonConfigService;
use addons\WechatMini\common\models\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\WechatMini\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "WechatMini";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

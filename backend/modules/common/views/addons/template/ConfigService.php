<?php

echo "<?php\n";
?>

namespace addons\<?= $model->name;?>\services;

use common\components\BaseAddonConfigService;
use addons\<?= $model->name;?>\common\forms\SettingForm;

/**
 * Class ConfigService
 *
 * @package addons\<?= $model->name;?>\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "<?= $model->name;?>";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;
}

<?php

namespace addons\Wechat\services;

use Yii;
use yii\helpers\Json;
use common\helpers\ArrayHelper;
use common\components\BaseAddonConfigService;
use addons\Wechat\common\models\SettingForm;
use addons\Wechat\merchant\forms\ReplyDefaultForm;
use addons\Wechat\common\enums\SpecialTypeEnum;
use addons\Wechat\common\enums\WechatEnum;

/**
 * Class ConfigService
 *
 * @package addons\Wechat\services
 */
class ConfigService extends BaseAddonConfigService
{
    /**
     * @var string
     */
    public $addonName = "Wechat";

    /**
     * @var SettingForm
     */
    public $settingForm = SettingForm::class;

    /**
     * @param int $merchant_id
     * @return ReplyDefaultForm
     */
    public function replyDefault($merchant_id = 0)
    {
        $setting = new ReplyDefaultForm();
        $setting->attributes = Yii::$app->services->addonsConfig->findConfigByCache($this->addonName, $merchant_id, true);

        return $setting;
    }

    /**
     * 获取特殊消息回复
     *
     * @return array
     */
    public function specialConfig($merchant_id = 0)
    {
        $config = Yii::$app->services->addonsConfig->findConfigByCache($this->addonName, $merchant_id, true);

        // 获取支持的模块
        $modules = Yii::$app->services->addons->findAll();

        $list = WechatEnum::getMap();
        $defaultList = [];
        foreach ($list as $key => $value) {
            $defaultList[$key]['title'] = $value;
            $defaultList[$key]['type'] = SpecialTypeEnum::KEYWORD;
            $defaultList[$key]['content'] = '';
            $defaultList[$key]['module'] = [];

            foreach ($modules as $module) {
                $wechat_message = [];

                if (!empty($module['wechat_message'])) {
                    $wechat_message = $module['wechat_message'];

                    if (!is_array($module['wechat_message'])) {
                        $wechat_message = Json::decode($module['wechat_message']);
                    }
                }

                foreach ($wechat_message as $item) {
                    if ($key == $item) {
                        $defaultList[$key]['module'][$module['name']] = $module['title'];
                        break;
                    }
                }
            }
        }

        if (isset($config['special']) && !empty($special = $config['special'])) {
            $defaultList = ArrayHelper::merge($defaultList, $special);
        }

        return $defaultList;
    }
}

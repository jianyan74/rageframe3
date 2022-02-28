<?php

namespace common\components;

use Yii;

/**
 * 公用获取配置服务
 *
 * Class BaseAddonConfigService
 * @package common\components
 */
class BaseAddonConfigService extends Service
{
    /**
     * @var array
     */
    private $setting = [];

    /**
     * @param $merchant_id
     * @return mixed
     */
    public function setting($merchant_id = 0)
    {
        // 查找是否已经获取
        if (isset($this->setting[$merchant_id])) {
            return $this->setting[$merchant_id];
        }

        $setting = new $this->settingForm;
        $setting->attributes = Yii::$app->services->addonsConfig->findConfigByCache($this->addonName, $merchant_id, true);
        $this->setting[$merchant_id] = $setting;

        return $setting;
    }

    /**
     * 自动获取配置
     *
     * @return mixed
     */
    public function autoSetting()
    {
        $merchant_id = Yii::$app->services->merchant->getAutoId();

        return $this->setting($merchant_id);
    }

    /**
     * 根据ID自动获取配置
     *
     * @return mixed
     */
    public function settingByMerchantId($merchant_id)
    {
        if ($merchant_id > 0 && Yii::$app->services->devPattern->isSAAS()) {
            return $this->setting($merchant_id);
        }

        return $this->setting();
    }
}

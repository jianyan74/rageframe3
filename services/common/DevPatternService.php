<?php

namespace services\common;

use Yii;
use common\enums\AppEnum;
use common\components\Service;
use common\enums\DevPatternEnum;

/**
 * 开发模式
 *
 * Class DevPatternService
 * @package services\common
 * @author jianyan74 <751393839@qq.com>
 */
class DevPatternService extends Service
{
    /**
     * 判断多商户
     *
     * @return bool
     */
    public function isB2B2C()
    {
        return Yii::$app->params['devPattern'] === DevPatternEnum::B2B2C;
    }

    /**
     * @return bool
     */
    public function isB2C()
    {
        return Yii::$app->params['devPattern'] === DevPatternEnum::B2C;
    }

    /**
     * @return bool
     */
    public function isSAAS()
    {
        return Yii::$app->params['devPattern'] === DevPatternEnum::SAAS;
    }

    /**
     * 调用位置是否在平台
     *
     * @return bool
     */
    public function isPlatformLocation()
    {
        if ($this->isB2B2C() && Yii::$app->id == AppEnum::BACKEND) {
            return true;
        }

        return false;
    }

    /**
     * 调用位置是否在商家
     *
     * @return bool
     */
    public function isMerchantLocation()
    {
        if ($this->isB2B2C() && Yii::$app->id == AppEnum::MERCHANT) {
            return true;
        }

        return false;
    }
}

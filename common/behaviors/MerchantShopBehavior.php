<?php

namespace common\behaviors;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * Trait MerchantShopBehavior
 * @package common\components
 */
trait MerchantShopBehavior
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => BlameableBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['merchant_id'],
            ],
            'value' => Yii::$app->services->merchant->getNotNullId(),
        ];

        $behaviors[] = [
            'class' => BlameableBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['shop_id'],
            ],
            'value' => Yii::$app->services->merchantShop->getNotNullId(),
        ];

        return $behaviors;
    }
}
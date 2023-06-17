<?php

namespace common\behaviors;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;

/**
 * Trait MerchantStoreBehavior
 * @package common\components
 */
trait MerchantStoreBehavior
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
                ActiveRecord::EVENT_BEFORE_INSERT => ['store_id'],
            ],
            'value' => Yii::$app->services->store->getNotNullId(),
        ];

        return $behaviors;
    }
}

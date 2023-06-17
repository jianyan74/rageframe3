<?php

namespace services\common;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\common\AttachmentCate;

/**
 * Class AttachmentCateService
 * @package services\common
 */
class AttachmentCateService extends Service
{
    /**
     * @return array
     */
    public function getMap($type)
    {
        return ArrayHelper::map($this->findAll($type), 'id', 'title');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll($type)
    {
        return AttachmentCate::find()
            ->where([
                'type' => $type,
                'status' => StatusEnum::ENABLED
            ])
            ->andWhere([
                'merchant_id' => Yii::$app->services->merchant->getNotNullId(),
                'store_id' => Yii::$app->services->store->getNotNullId(),
            ])
            ->asArray()
            ->all();
    }
}

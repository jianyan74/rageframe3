<?php

namespace services\common;

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
    public function getMap()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'title');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return AttachmentCate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->asArray()
            ->all();
    }
}
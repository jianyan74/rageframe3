<?php

namespace services\common;

use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\models\common\Archives;

/**
 * Class ArchivesService
 * @package services\common
 */
class ArchivesService
{
    /**
     * 商户最后一条认证信息
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMerchantId($merchant_id)
    {
        return Archives::find()
            ->where(['merchant_id' => $merchant_id])
            ->andWhere(['member_type' => MemberTypeEnum::MERCHANT])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }
}
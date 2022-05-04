<?php

namespace services\member;

use common\components\Service;
use common\enums\StatusEnum;
use common\models\member\Invoice;

/**
 * Class InvoiceService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceService extends Service
{
    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findDefaultByMemberId($member_id)
    {
        return Invoice::find()
            ->where(['member_id' => $member_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['is_default' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $id
     * @param $member_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findById($id, $member_id)
    {
        return Invoice::find()
            ->where(['id' => $id, 'member_id' => $member_id, 'status' => StatusEnum::ENABLED])
            ->one();
    }
}

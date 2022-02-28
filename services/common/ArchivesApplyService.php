<?php

namespace services\common;

use common\enums\AuditStatusEnum;
use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\models\common\ArchivesApply;

/**
 * Class ArchivesApplyService
 * @package services\common
 */
class ArchivesApplyService
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getGroupStateCount($member_type = MemberTypeEnum::MERCHANT)
    {
        return ArchivesApply::find()
            ->select(['audit_status', 'count(id) as count'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['member_type' => $member_type])
            ->groupBy('audit_status')
            ->asArray()
            ->all();
    }

    /**
     * @return bool|int|string
     */
    public function getApplyCount($merchant_id = '', $member_type = MemberTypeEnum::MERCHANT)
    {
        return ArchivesApply::find()
                ->where(['status' => StatusEnum::ENABLED, 'audit_status' => AuditStatusEnum::DISABLED])
                ->andWhere(['member_type' => $member_type])
                ->andFilterWhere(['merchant_id' => $merchant_id])
                ->count() ?? 0;
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return ArchivesApply::find()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * 商户最后一条认证信息
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findLastByMerchantId($merchant_id)
    {
        return ArchivesApply::find()
            ->where(['merchant_id' => $merchant_id])
            ->andWhere(['member_type' => MemberTypeEnum::MERCHANT])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('id desc')
            ->one();
    }
}
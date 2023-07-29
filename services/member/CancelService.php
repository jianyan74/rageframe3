<?php

namespace services\member;

use Yii;
use yii\web\UnprocessableEntityHttpException;
use common\components\Service;
use common\enums\AuditStatusEnum;
use common\enums\StatusEnum;
use common\models\member\Cancel;
use common\models\member\Member;

/**
 * Class CancelService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class CancelService extends Service
{
    /**
     * @param Member $member
     * @return true
     * @throws UnprocessableEntityHttpException
     */
    public function create(Member $member)
    {
        if ($lastModel = $this->findLastByMemberId($member->id)) {
            if ($lastModel['audit_status'] == StatusEnum::DISABLED) {
                throw new UnprocessableEntityHttpException('审核中，请不要重复申请');
            }

            if ($lastModel['audit_status'] == StatusEnum::ENABLED) {
                throw new UnprocessableEntityHttpException('审核通过，请不要重复申请');
            }
        }

        $model = new Cancel();
        $model->member_id = $member->id;
        // 获取配置
        $config = Yii::$app->services->addonsConfig->findConfigByCache('Member', $member->merchant_id, true);
        if (isset($config['cancel_audit_status']) && $config['cancel_audit_status'] == StatusEnum::DISABLED) {
            $model->audit_status = AuditStatusEnum::ENABLED;
            $model->audit_time = time();
            $model->save();
            // 会员注销
            $member->status = StatusEnum::DELETE;
            $member->save();
        } else {
            $model->save();

            throw new UnprocessableEntityHttpException('申请成功, 请等待审核');
        }

        return true;
    }

    /**
     * @return int|string
     */
    public function getApplyCount()
    {
        return Cancel::find()
            ->where(['audit_status' => AuditStatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->count();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Cancel::find()
            ->where(['id' => $id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findLastByMemberId($member_id)
    {
        return Cancel::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['member_id' => $member_id])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->asArray()
            ->one();
    }
}

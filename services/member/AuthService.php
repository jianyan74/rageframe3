<?php

namespace services\member;

use Yii;
use common\helpers\ArrayHelper;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\member\Auth;
use common\enums\MemberTypeEnum;

/**
 * Class AuthService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class AuthService extends Service
{
    /**
     * @param $data
     * @return Auth
     * @throws \Exception
     */
    public function create($data)
    {
        $model = new Auth();
        $model->loadDefaultValues();
        $model->attributes = $data;
        !$model->save() && $this->error($model);

        return $model;
    }

    /**
     * @param $oauthClient
     * @param $memberId
     * @return array|bool|\yii\db\ActiveRecord
     */
    public function unBind($oauthClient, $memberId)
    {
        $model = $this->findByMemberIdOauthClient($oauthClient, $memberId);
        if (!$model) {
            return true;
        }

        $model->status = StatusEnum::DISABLED;
        $model->save();

        return $model;
    }

    /**
     * @param $memberId
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByMemberId($memberId)
    {
        return Auth::find()
            ->where(['member_id' => $memberId])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $memberId
     * @return bool|int|string
     */
    public function getCountByMemberId($memberId)
    {
        return Auth::find()
            ->where(['member_id' => $memberId])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->count() ?? 0;
    }

    /**
     * @param int $merchant_id
     * @param int $memberType
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByMemberType($merchant_id = 0, $memberType = MemberTypeEnum::MEMBER)
    {
        return Auth::find()
            ->where(['member_type' => $memberType])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->all();
    }

    /**
     * @param $oauthClient
     * @param $oauthClientUserId
     * @param int $memberType
     * @return array|\yii\db\ActiveRecord|null|Auth
     */
    public function findOauthClient($oauthClient, $oauthClientUserId, $memberType = MemberTypeEnum::MEMBER)
    {
        return Auth::find()
            ->where([
                'oauth_client' => $oauthClient,
                'oauth_client_user_id' => $oauthClientUserId,
                'member_type' => $memberType,
            ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->one();
    }

    /**
     * @param $oauthClient
     * @param $oauthClientUserId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMemberIdOauthClient($oauthClient, $memberId)
    {
        return Auth::find()
            ->where(['oauth_client' => $oauthClient, 'member_id' => $memberId])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->one();
    }

    /**
     * @param $oauthClient
     * @param $oauthClientUserId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findOauthClientByApp($oauthClient, $oauthClientUserId)
    {
        return Auth::find()
            ->where(['oauth_client' => $oauthClient, 'oauth_client_user_id' => $oauthClientUserId])
            ->andWhere(['status' => StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->one();
    }

    /**
     * @param $unionId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByUnionId($unionId, $memberType = MemberTypeEnum::MEMBER)
    {
        return Auth::find()
            ->where(['unionid' => $unionId, 'member_type' => $memberType])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->one();
    }

    /**
     * @param $unionId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMerchantId($merchantId, $memberType = MemberTypeEnum::MEMBER)
    {
        return Auth::find()
            ->where(['merchant_id' => $merchantId, 'member_type' => $memberType])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->one();
    }

    /**
     * @param $memberId
     * @param $oauthClient
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMemberIdAndOauthClient($memberId, $oauthClient)
    {
        return Auth::find()
            ->where(['member_id' => $memberId, 'oauth_client' => $oauthClient])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $unionId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findAllByUnionId($unionId, $memberType = MemberTypeEnum::MEMBER)
    {
        return Auth::find()
            ->where(['unionid' => $unionId, 'member_type' => $memberType])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->all();
    }
}

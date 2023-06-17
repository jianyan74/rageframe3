<?php

namespace services\api;

use Yii;
use yii\db\ActiveRecord;
use common\components\Service;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\member\Member;
use common\enums\DevPatternEnum;
use common\models\api\AccessToken;

/**
 * Class AccessTokenService
 * @package services\api
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenService extends Service
{
    /**
     * @var bool
     */
    public $cache = false;
    /**
     * @var int
     */
    public $timeout = 720;


    /**
     * 获取token
     *
     * @param Member $member
     * @param $group
     * @param int $cycle_index 重新获取次数
     * @return array
     * @throws \yii\base\Exception
     */
    public function getAccessToken(Member $member, $group, $cycle_index = 1)
    {
        $model = $this->findModel($member->id, $member->type, $group);
        $model->member_id = $member->id;
        $model->member_type = $member->type;
        $model->merchant_id = $member->merchant_id;
        $model->group = $group;
        // 删除缓存
        !empty($model->access_token) && Yii::$app->cache->delete($this->getCacheKey($model->access_token));
        $model->refresh_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->access_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->status = StatusEnum::ENABLED;

        if (!$model->save()) {
            if ($cycle_index <= 3) {
                $cycle_index++;
                return self::getAccessToken($member, $group, $cycle_index);
            }

            $this->error($model);
        }

        $result = [];
        $result['refresh_token'] = $model->refresh_token;
        $result['access_token'] = $model->access_token;
        $result['expiration_time'] = Yii::$app->params['user.accessTokenExpire'];

        // 关联账号信息
        $account = $member->account;
        $memberLevel = $member->memberLevel;
        $member = ArrayHelper::toArray($member);
        unset($member['password_hash'], $member['auth_key'], $member['password_reset_token'], $member['access_token'], $member['refresh_token']);
        $result['member'] = $member;
        $result['member']['account'] = ArrayHelper::toArray($account);
        $result['member']['memberLevel'] = ArrayHelper::toArray($memberLevel);

        // 写入缓存
        $this->cache === true && Yii::$app->cache->set($this->getCacheKey($model->access_token), $model, $this->timeout);

        return $result;
    }

    /**
     * @param $token
     * @param $type
     * @return array|mixed|null|ActiveRecord
     */
    public function getTokenToCache($token, $type, $cache = false)
    {
        if ($cache == false && $this->cache == false) {
            return $this->findByAccessToken($token);
        }

        $key = $this->getCacheKey($token);
        if (!($model = Yii::$app->cache->get($key))) {
            $model = $this->findByAccessToken($token);
            Yii::$app->cache->set($key, $model, $this->timeout);
        }

        return $model;
    }

    /**
     * 禁用token
     *
     * @param $access_token
     */
    public function disableByAccessToken($access_token)
    {
        $this->cache === true && Yii::$app->cache->delete($this->getCacheKey($access_token));

        if ($model = $this->findByAccessToken($access_token)) {
            $model->status = StatusEnum::DISABLED;
            return $model->save();
        }

        return false;
    }

    /**
     * 获取token
     *
     * @param $token
     * @return array|null|ActiveRecord|AccessToken
     */
    public function findByAccessToken($token)
    {
        return AccessToken::find()
            ->where(['access_token' => $token, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $access_token
     * @return string
     */
    protected function getCacheKey($access_token)
    {
        return 'apiAccessToken' . $access_token;
    }

    /**
     * 返回模型
     *
     * @param $member_id
     * @param $group
     * @return array|AccessToken|null|ActiveRecord
     */
    protected function findModel($member_id, $member_type, $group)
    {
        if (empty(($model = AccessToken::find()->where([
            'member_id' => $member_id,
            'member_type' => $member_type,
            'group' => $group
        ])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            $model = new AccessToken();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}

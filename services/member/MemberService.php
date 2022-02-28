<?php

namespace services\member;

use Yii;
use common\models\base\User;
use common\enums\AppEnum;
use common\enums\StatusEnum;
use common\models\member\Member;
use common\enums\MemberTypeEnum;
use common\helpers\EchantsHelper;

/**
 * Class MemberService
 * @package services\member
 */
class MemberService
{
    /**
     * 用户
     *
     * @var Member
     */
    protected $member;

    /**
     * @param $id
     * @return array|Member|\yii\db\ActiveRecord|null
     */
    public function get($id)
    {
        if (!$this->member || $this->member['id'] != $id) {
            $this->member = $this->findById($id);
        }

        return $this->member;
    }

    /**
     * @param Member $member
     * @return $this
     */
    public function set(Member $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * 获取当前登录的用户ID
     *
     * @return int|string
     */
    public function getAutoId()
    {
        if (in_array(Yii::$app->id, AppEnum::api())) {
            $member_id = Yii::$app->user->identity->member_id ?? 0;
            if (empty($member_id)) {
                return Yii::$app->user->id ?? 0;
            }

            return $member_id;
        }

        return Yii::$app->user->id ?? 0;
    }

    /**
     * @return int|string
     */
    public function getCountByType($type = MemberTypeEnum::MEMBER, $merchant_id = '')
    {
        return Member::find()
            ->select('id')
            ->andWhere(['>', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => $type])
            ->andFilterWhere(['merchant_id' => $merchant_id])
            ->count();
    }

    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenCountStat($type)
    {
        $fields = [
            'count' => '注册会员人数',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return Member::find()
                ->select(['count(id) as count', "from_unixtime(created_at, '$formatting') as time"])
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()])
                ->andWhere(['type' => MemberTypeEnum::MEMBER])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * 记录行为
     *
     * @param User $member
     * @param bool $saveAction
     * @throws \yii\base\InvalidConfigException
     */
    public function lastLogin(User $member)
    {
        // 记录访问次数
        $member->visit_count += 1;
        $member->last_time = time();
        $member->last_ip = Yii::$app->services->base->getUserIp();
        $member->save();
    }

    /**
     * @param $level
     * @param $type
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function hasLevel($level, $type, $merchant_id)
    {
        return Member::find()
            ->where(['current_level' => $level])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['merchant_id' => $merchant_id])
            ->andWhere(['type' => $type])
            ->one();
    }

    /**
     * 写入条件查询
     *
     * @param array $condition
     * @return array|\yii\db\ActiveRecord|null|Member
     */
    public function findByCondition(array $condition)
    {
        return Member::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->one();
    }

    /**
     * 写入条件查询
     *
     * @param array $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findAllByCondition(array $condition)
    {
        return Member::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null|Member
     */
    public function findById($id)
    {
        return Member::find()
            ->where(['id' => $id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->one();
    }

    /**
     * @param $promoCode
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByPromoCode($promoCode)
    {
        return Member::find()
            ->where(['promo_code' => $promoCode])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->one();
    }
}

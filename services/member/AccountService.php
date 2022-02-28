<?php

namespace services\member;

use common\enums\MemberTypeEnum;
use common\enums\StatusEnum;
use common\models\member\Account;

/**
 * Class AccountService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class AccountService
{
    /**
     * 获取商户下的用户账号统计
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSumByMerchant()
    {
        return Account::find()
            ->select([
                'sum(user_money) as user_money',
                'sum(give_money) as give_money',
                'sum(user_integral) as user_integral',
                'sum(consume_money) as consume_money',
                'sum(accumulate_money) as accumulate_money',
                'sum(accumulate_drawn_money) as accumulate_drawn_money',
            ])
            ->where([
                'member_id' => 0,
                'member_type' => MemberTypeEnum::MERCHANT,
                'status' => StatusEnum::ENABLED
            ])
            ->asArray()
            ->one();
    }

    /**
     * 根据类型获取统计
     *
     * @param int $type
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getSumByType($type = MemberTypeEnum::MEMBER)
    {
        return Account::find()
            ->select([
                'sum(user_money) as user_money',
                'sum(give_money) as give_money',
                'sum(user_integral) as user_integral',
                'sum(consume_money) as consume_money',
                'sum(accumulate_money) as accumulate_money',
                'sum(accumulate_drawn_money) as accumulate_drawn_money',
            ])
            ->where([
                'member_type' => $type,
                'status' => StatusEnum::ENABLED
            ])
            ->asArray()
            ->one();
    }

    /**
     * @param $member_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMemberId($member_id)
    {
        return Account::find()
            ->where(['member_id' => $member_id])
            ->one();
    }
}

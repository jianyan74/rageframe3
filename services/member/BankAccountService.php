<?php

namespace services\member;

use common\components\Service;
use common\enums\AccountTypeEnum;
use common\enums\StatusEnum;
use common\models\member\BankAccount;

/**
 * Class BankAccountService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class BankAccountService extends Service
{
    /**
     * 查询商家的提现账号
     *
     * @param array $condition
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByMerchantId($merchant_id)
    {
        $condition = [
            'merchant_id' => $merchant_id,
            'member_id' => 0,
        ];

        return $this->findAllByCondition($condition);
    }

    /**
     * 获取默认地址
     *
     * @param $member_id
     * @return array|null|\yii\db\ActiveRecord|BankAccount
     */
    public function findDefaultByMemberId($member_id)
    {
        return BankAccount::find()
            ->where([
                'member_id' => $member_id,
                'status' => StatusEnum::ENABLED,
                'is_default' => StatusEnum::ENABLED
            ])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 查询商家的默认提现账号
     *
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findMerchantDefault($merchant_id)
    {
        $condition = [
            'merchant_id' => $merchant_id,
            'member_id' => 0,
            'is_default' => StatusEnum::ENABLED,
        ];

        return $this->findByCondition($condition);
    }

    /**
     * @param $data
     * @return array
     */
    public function getMapList($data)
    {
        $map = [];
        foreach ($data as $item) {
            if (empty($item['account_number'])) {
                continue;
            }

            $tmp = [];
            $tmp[] = $item['account_type_name'];
            if ($item['account_type'] == AccountTypeEnum::ALI) {
                $tmp[] = $item['account_number'];
            }

            if ($item['account_type'] == AccountTypeEnum::UNION) {
                $tmp[] = $item['bank_name'];
                $tmp[] = $item['account_number'];
            }

            if ($item['account_type'] == AccountTypeEnum::WECHAT) {
                $tmp[] = $item['account_number'];
            }

            $map[$item['id']] = implode(" | ", $tmp);
        }

        return $map;
    }

    /**
     * @param array $condition
     * @return array|\yii\db\ActiveRecord[]|BankAccount
     */
    public function findByCondition(array $condition)
    {
        return BankAccount::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->one();
    }

    /**
     * @param array $condition
     * @return array|\yii\db\ActiveRecord[]|BankAccount
     */
    public function findAllByCondition(array $condition)
    {
        return BankAccount::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->all();
    }

    /**
     * @param $id
     * @param $member_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id, $member_id = '')
    {
        return BankAccount::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['member_id' => $member_id])
            ->one();
    }

    /**
     * @param int $member_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllByMemberId(int $member_id)
    {
        return BankAccount::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['member_id' => $member_id])
            ->all();
    }
}

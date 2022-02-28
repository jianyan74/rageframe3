<?php

namespace services\member;

use yii\db\ActiveRecord;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\member\Address;

/**
 * Class AddressService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class AddressService extends Service
{
    /**
     * 获取默认地址
     *
     * @param $member_id
     * @return array|null|ActiveRecord|Address
     */
    public function findDefaultByMemberId($member_id)
    {
        return Address::find()
            ->where([
                'member_id' => $member_id,
                'status' => StatusEnum::ENABLED,
                'is_default' => StatusEnum::ENABLED,
            ])
            ->one();
    }

    /**
     * @param $id
     * @param $member_id
     * @return array|null|ActiveRecord|Address
     */
    public function findById($id, $member_id)
    {
        return Address::find()
            ->where(['id' => $id, 'member_id' => $member_id, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $member_id
     * @return array|ActiveRecord[]
     */
    public function findByMemberId($member_id)
    {
        return Address::find()
            ->where(['member_id' => $member_id, 'status' => StatusEnum::ENABLED])
            ->orderBy(['is_default desc'])
            ->asArray()
            ->all();
    }
}

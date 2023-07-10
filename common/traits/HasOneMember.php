<?php

namespace common\traits;

use common\enums\StatusEnum;
use common\models\member\Member;

/**
 * Trait HasOneMember
 * @package common\traits
 */
trait HasOneMember
{
    /**
     * 用户信息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * 用户信息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBaseMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id'])->select(['id', 'nickname', 'mobile', 'email', 'head_portrait']);
    }
}

<?php

namespace services\common;

use common\enums\StatusEnum;
use common\models\common\NotifyAnnounce;

/**
 * Class NotifyAnnounceService
 * @package services\common
 */
class NotifyAnnounceService
{
    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return NotifyAnnounce::find()
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }
}
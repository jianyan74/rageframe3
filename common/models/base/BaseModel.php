<?php

namespace common\models\base;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\behaviors\SoftDeleteBehavior;

/**
 * Class BaseModel
 * @package common\models\common
 * @author jianyan74 <751393839@qq.com>
 */
class BaseModel extends ActiveRecord
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => SoftDeleteBehavior::class,
                'attribute' => 'status'
            ],
        ];
    }
}

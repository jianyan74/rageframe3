<?php

namespace common\forms;

use common\behaviors\TaggableQueryBehavior;

/**
 * Class PostQueryForm
 * @package common\forms
 * @author jianyan74 <751393839@qq.com>
 */
class PostQueryForm extends \yii\db\ActiveQuery
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::class,
        ];
    }
}

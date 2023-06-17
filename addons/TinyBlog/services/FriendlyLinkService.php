<?php

namespace addons\TinyBlog\services;

use common\enums\StatusEnum;
use addons\TinyBlog\common\models\FriendlyLink;

/**
 * Class FriendlyLinkService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class FriendlyLinkService
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return FriendlyLink::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }
}

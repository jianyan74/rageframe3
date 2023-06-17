<?php

namespace addons\TinyBlog\services;

use addons\TinyBlog\common\models\Adv;
use common\components\Service;
use common\enums\StatusEnum;

/**
 * Class AdvService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class AdvService extends Service
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function newest()
    {
        return Adv::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['<', 'start_time', time()])
            ->andWhere(['>', 'end_time', time()])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('sort asc, id desc')
            ->asArray()
            ->one();
    }
}

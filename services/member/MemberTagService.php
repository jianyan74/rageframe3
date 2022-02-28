<?php

namespace services\member;

use Yii;
use common\models\member\Tag;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * Class MemberTagService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class MemberTagService
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findById($id)
    {
        return Tag::find()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'title');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Tag::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getNotNullId()])
            ->all();
    }
}

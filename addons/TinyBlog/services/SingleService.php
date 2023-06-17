<?php

namespace addons\TinyBlog\services;

use addons\TinyBlog\common\models\Single;
use common\enums\StatusEnum;

/**
 * Class SingleService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class SingleService
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findById($id)
    {
        return Single::find()
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByName($name)
    {
        return Single::find()
            ->where(['name' => $name])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Single::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }
}

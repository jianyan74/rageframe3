<?php

namespace addons\TinyBlog\services;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use addons\TinyBlog\common\models\Cate;

/**
 * Class CateService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class CateService
{
    /**
     * @return array
     */
    public function getMapList()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'title');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findById($id)
    {
        return Cate::find()
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Cate::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }
}

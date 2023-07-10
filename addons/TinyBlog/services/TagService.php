<?php

namespace addons\TinyBlog\services;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use addons\TinyBlog\common\models\Tag;

/**
 * Class TagService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class TagService
{
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findById($id)
    {
        return Tag::find()
            ->where(['id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @return array
     */
    public function getMapList()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'title');
    }

    /**
     * @return array
     */
    public function getTitleMapList()
    {
        return ArrayHelper::map($this->findAll(), 'title', 'title');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Tag::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
    }
}

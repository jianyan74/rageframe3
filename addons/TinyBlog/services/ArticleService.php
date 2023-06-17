<?php

namespace addons\TinyBlog\services;

use Yii;
use common\enums\StatusEnum;
use addons\TinyBlog\common\models\Article;
use addons\TinyBlog\common\enums\ArticlePositionEnum;

/**
 * Class ArticleService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class ArticleService
{
    /**
     * 上一篇
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getPrev($id)
    {
        return Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['<', 'id', $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->select(['id', 'title'])
            ->orderBy('id desc')
            ->one();
    }

    /**
     * 下一篇
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getNext($id)
    {
        return Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['>', 'id', $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
            ->select(['id', 'title'])
            ->orderBy('id asc')
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function hot($limit = 6)
    {
        return Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(ArticlePositionEnum::position(ArticlePositionEnum::HOT))// 推荐位 位运算查询
            ->orderBy('created_at desc')
            ->asArray()
            ->limit($limit)
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function newest($limit = 6)
    {
        return Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('created_at desc')
            ->asArray()
            ->limit($limit)
            ->all();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function recommend($limit = 8)
    {
        return Article::find()
            ->select(['id', 'merchant_id', 'cate_id', 'title', 'description', 'cover', 'author', 'view', 'created_at'])
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('rand()')
            ->asArray()
            ->limit($limit)
            ->all();
    }
}

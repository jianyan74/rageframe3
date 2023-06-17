<?php

namespace addons\TinyBlog\services;

use Yii;
use addons\TinyBlog\common\models\TagMap;

/**
 * Class TagMapService
 * @package addons\TinyBlog\services
 * @author jianyan74 <751393839@qq.com>
 */
class TagMapService
{
    /**
     * @param $article_id
     * @param $tags
     * @return bool
     * @throws \yii\db\Exception
     */
    public function create($article_id, $tags)
    {
        // 删除原有标签关联
        TagMap::deleteAll(['article_id' => $article_id]);
        if ($article_id && !empty($tags)) {
            $data = [];

            foreach ($tags as $v) {
                $data[] = [$v, $article_id];
            }

            $field = ['tag_id', 'article_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(TagMap::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }

    /**
     * @param $article_id
     * @return array
     */
    public function findTagId($article_id)
    {
        return TagMap::find()
            ->select(['tag_id'])
            ->where(['article_id' => $article_id])
            ->column();
    }
}

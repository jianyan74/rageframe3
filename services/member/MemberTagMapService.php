<?php

namespace services\member;

use Yii;
use common\helpers\ArrayHelper;
use common\models\member\TagMap;

/**
 * Class MemberTagMapService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class MemberTagMapService
{
    /**
     * @param $member_id
     * @return array
     */
    public function getMemberByTagId($tag_id)
    {
        return ArrayHelper::getColumn(TagMap::find()
            ->where(['tag_id' => $tag_id])
            ->with(['baseMember'])
            ->asArray()
            ->all(), 'baseMember');
    }

    /**
     * @param $member_id
     * @return array
     */
    public function findIdsByMemberId($member_id)
    {
        return TagMap::find()
            ->select(['tag_id'])
            ->where(['member_id' => $member_id])
            ->column();
    }

    /**
     * @param $member_id
     * @param $tags
     * @return bool
     * @throws \yii\db\Exception
     */
    public function addTags($member_id, $tags)
    {
        // 删除原有标签关联
        TagMap::deleteAll(['member_id' => $member_id]);
        if ($member_id && !empty($tags)) {
            $data = [];

            foreach ($tags as $v) {
                $data[] = [$v, $member_id];
            }

            $field = ['tag_id', 'member_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(TagMap::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

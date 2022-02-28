<?php

namespace services\common;

use common\enums\NotifyTypeEnum;
use common\helpers\ArrayHelper;
use common\enums\StatusEnum;
use common\models\common\NotifyMember;

/**
 * Class NotifyMemberService
 * @package services\common
 */
class NotifyMemberService
{
    /**
     * 获取用户消息列表
     *
     * @param $merchant_id
     */
    public function getNotReadNotify($merchant_id, $is_read = 0)
    {
        $data = NotifyMember::find()
            ->select(['type', 'max(created_at) as created_at', 'count(id) as count'])
            ->where(['status' => StatusEnum::ENABLED, 'is_read' => $is_read])
            ->andWhere(['merchant_id' => $merchant_id])
            ->groupBy('type')
            ->asArray()
            ->all();

        $count = 0;
        foreach ($data as &$datum) {
            $count += $datum['count'];
            $datum['title'] = NotifyTypeEnum::getValue($datum['type']);
        }

        return [
            ArrayHelper::arrayKey($data, 'type'),
            $count
        ];
    }

    /**
     * 更新指定的notify，把isRead属性设置为true
     *
     * @param $member_id
     */
    public function readById($member_id, $merchant_id, $notifyIds)
    {
        NotifyMember::updateAll(['is_read' => 1, 'read_member_id' => $member_id, 'updated_at' => time()], [
            'and',
            ['merchant_id' => $merchant_id, 'is_read' => 0],
            ['in', 'id', $notifyIds]
        ]);
    }

    /**
     * 删除指定的notify，把isRead属性设置为true
     *
     * @param $member_id
     */
    public function deleteById($merchant_id, $notifyIds)
    {
        NotifyMember::deleteAll([
            'and',
            ['merchant_id' => $merchant_id],
            ['in', 'id', $notifyIds]
        ]);
    }

    /**
     * @param $member_id
     * @param $merchant_id
     * @param $notifyIds
     */
    public function readByNotifyId($member_id, $merchant_id, $notifyIds)
    {
        NotifyMember::updateAll(['is_read' => 1, 'read_member_id' => $member_id, 'updated_at' => time()], [
            'and',
            ['merchant_id' => $merchant_id, 'is_read' => 0],
            ['in', 'notify_id', $notifyIds]
        ]);
    }

    /**
     * 全部设为已读
     *
     * @param $member_id
     */
    public function readAll($member_id, $merchant_id)
    {
        NotifyMember::updateAll(['is_read' => 1, 'read_member_id' => $member_id, 'updated_at' => time()], ['merchant_id' => $merchant_id, 'is_read' => 0]);
    }
}
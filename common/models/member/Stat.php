<?php

namespace common\models\member;

use common\models\base\BaseModel;
use common\traits\HasOneMember;

/**
 * This is the model class for table "{{%addon_tiny_vision_member_stat}}".
 *
 * @property int $id
 * @property int|null $merchant_id 商户id
 * @property int|null $store_id 店铺id
 * @property int|null $member_id 用户id
 * @property int|null $nice_num 点赞数量
 * @property int|null $disagree_num 不赞同数量
 * @property int|null $transmit_num 转发数量
 * @property int|null $comment_num 评论数量
 * @property int|null $collect_num 收藏
 * @property int|null $report_num 举报数量
 * @property int|null $recommend_num 推荐数量
 * @property int|null $follow_num 关注人数
 * @property int|null $allowed_num 被关注人数
 * @property int|null $view 浏览量
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Stat extends BaseModel
{
    use HasOneMember;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member_stat}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'merchant_id',
                    'store_id',
                    'member_id',
                    'nice_num',
                    'disagree_num',
                    'transmit_num',
                    'comment_num',
                    'collect_num',
                    'report_num',
                    'recommend_num',
                    'follow_num',
                    'allowed_num',
                    'view',
                    'status',
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户id',
            'store_id' => '店铺id',
            'member_id' => '用户id',
            'nice_num' => '点赞数量',
            'disagree_num' => '不赞同数量',
            'transmit_num' => '转发数量',
            'comment_num' => '评论数量',
            'collect_num' => '收藏',
            'report_num' => '举报数量',
            'recommend_num' => '推荐数量',
            'follow_num' => '关注人数',
            'allowed_num' => '被关注人数',
            'view' => '浏览量',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
